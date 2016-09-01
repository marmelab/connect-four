<?php

namespace ConnectFour;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Game
{
    const WAITING = 0;
    const PLAYING = 1;
    const FINISHED = 2;

    const FIRST_PLAYER_COLOR = 'yellow';
    const SECOND_PLAYER_COLOR = 'red';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="starting_player_id", referencedColumnName="id")
     */
    private $startingPlayer;

    private $currentPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="first_player_id", referencedColumnName="id")
     */
    private $firstPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="second_player_id", referencedColumnName="id")
     */
    private $secondPlayer;

    private $board;

    private $winner;

    private $finished;

    /**
     * @ORM\OneToMany(targetEntity="Move", mappedBy="game", cascade={"all"})
     */
    private $moves;

    public function __construct()
    {
        $this->board = new Board();
        $this->moves = new ArrayCollection();
    }

    public function addPlayer(Player $player)
    {
        if (!$this->firstPlayer) {
            $this->firstPlayer = $player;
        } elseif (!$this->secondPlayer) {
            $this->secondPlayer = $player;
            // here both players are assigned, we can decide who starts
            $this->startingPlayer = rand(0, 1) == 0 ? $this->firstPlayer : $this->secondPlayer;
            $this->currentPlayer = $this->startingPlayer;
        } else {
            throw new TooManyPlayersException();
        }
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getCurrentPlayer() : Player
    {
        return $this->currentPlayer;
    }

    public function getSecondPlayer() : Player
    {
        return $this->secondPlayer;
    }

    public function getFirstPlayer() : Player
    {
        return $this->firstPlayer;
    }

    public function getStatus() : string
    {
        if ($this->isFinished()) {
            return self::FINISHED;
        } elseif ((bool) $this->firstPlayer && (bool) $this->secondPlayer) {
            return self::PLAYING;
        }

        return self::WAITING;
    }

    public function isFinished() // : bool
    {
        return $this->finished;
    }

    public function getWinner() // : Player - Can't be null, waiting for php 7.1
    {
        return $this->winner;
    }

    public function getBoard() : Board
    {
        return $this->board;
    }

    public function addDisc(int $column, Player $player, bool $addMove = true)
    {
        if ($this->isFinished()) {
            throw new GameFinishedException();
        }
        if ($this->currentPlayer != $player) {
            throw new NotYourTurnException();
        }

        $rowAdded = $this->getBoard()->addDisc($column, $player);

        if ($addMove) {
            $move = new Move($this, $column, $player, new \DateTime());
            $this->getMoves()->add($move);
        }

        if ($this->isDiscWinner($column, $rowAdded)) {
            $this->winner = $player;
            $this->finished = true;

            return;
        }

        if ($this->getBoard()->isFull()) {
            $this->finished = true;

            return;
        }

        $this->switchPlayer();
    }

    private function switchPlayer()
    {
        $this->currentPlayer = ($this->firstPlayer == $this->currentPlayer) ? $this->secondPlayer : $this->firstPlayer;
    }

    // TODO: use better algorithm
    private function isDiscWinner(int $column, int $row)
    {
        $player = $this->getBoard()->getDisc($column, $row)->getPlayer();
        $horizontalCount = 1 + $this->countConsecutiveDiscsAsideOf($column, $row, $player, -1, 0)
        + $this->countConsecutiveDiscsAsideOf($column, $row, $player, +1, 0);
        if ($horizontalCount >= 4) {
            return true;
        }

        $verticalCount = 1 + $this->countConsecutiveDiscsAsideOf($column, $row, $player, 0, -1)
        + $this->countConsecutiveDiscsAsideOf($column, $row, $player, 0, +1);
        if ($verticalCount >= 4) {
            return true;
        }

        $topLeftBottomRightCount = 1 + $this->countConsecutiveDiscsAsideOf($column, $row, $player, -1, -1)
        + $this->countConsecutiveDiscsAsideOf($column, $row, $player, +1, +1);
        if ($topLeftBottomRightCount >= 4) {
            return true;
        }

        $bottomLeftTopRightCount = 1 + $this->countConsecutiveDiscsAsideOf($column, $row, $player, -1, +1)
        + $this->countConsecutiveDiscsAsideOf($column, $row, $player, +1, -1);
        if ($bottomLeftTopRightCount >= 4) {
            return true;
        }

        return false;
    }

    /**
     *   Scans a side of a position (exclusive) by horizontal and vertical steps.
     */
    private function countConsecutiveDiscsAsideOf(int $column, int $row, Player $player, int $columnStep = 1, int $rowStep = 1)
    {
        $cnt = 0;
        $maxPositions = 3;
        $x = $column + $columnStep;
        $y = $row + $rowStep;
        while (
            $this->isHorizontallyInBounds($x, $column, $columnStep, $maxPositions) &&
            $this->isVerticallyInBounds($y, $row, $rowStep, $maxPositions)
        ) {
            try {
                $disc = $this->getBoard()->getDisc($x, $y, $player);
                if (!$disc) {
                    // if no disk found, stop looking
                    break;
                }
                ++$cnt;
                $x += $columnStep;
                $y += $rowStep;
            } catch (OutOfBoardException $e) {
                // if we're being out of the board, stop looking for discs
                break;
            }
        }

        return $cnt;
    }

    private function isHorizontallyInBounds($x, $column, $columnStep, $maxPositions)
    {
        if ($columnStep < 0) {
            return $x >= $column + ($maxPositions * $columnStep);
        } else {
            return $x <= $column + ($maxPositions * $columnStep);
        }
    }

    private function isVerticallyInBounds($y, $row, $rowStep, $maxPositions)
    {
        if ($rowStep < 0) {
            return $y >= $row + ($maxPositions * $rowStep);
        } else {
            return $y <= $row + ($maxPositions * $rowStep);
        }
    }

    public function replayMoves()
    {
        $this->board = new Board();
        $this->currentPlayer = $this->startingPlayer;
        foreach ($this->moves as $move) {
            $move->getPlayer()->dropDisc($this, $move->getColumn(), false);
        }
    }

    public function getPlayerColor(Player $player) : string
    {
        return $this->getFirstPlayer() == $player
            ? self::FIRST_PLAYER_COLOR
            : self::SECOND_PLAYER_COLOR;
    }

    public function setMoves($moves)
    {
        $this->moves = $moves;
    }

    public function getMoves()
    {
        return $this->moves;
    }
}
