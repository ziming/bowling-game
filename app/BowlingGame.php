<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class BowlingGame extends Model
{
    const FRAMES_PER_GAME = 10;

    /**
     * @var array
     */
    protected $inputFrames;

    /**
     * @var array
     */
    protected $rolls;


    public function setInputFrames(array $frames)
    {
        $this->validateInputFrames($frames);
        $this->inputFrames = $frames;
        $this->rolls = array_collapse($frames);

        // make it a fluent interface because why not?
        return $this;
    }

    /**
     * @return array the score history of the game. Spare points are not required.
     */
    public function getScoreHistory(): array
    {
        $scoreHistory = [];
        $rollIndex = 0;
        $totalScore = 0;

        for ($i = 0; $i < self::FRAMES_PER_GAME; $i++) {
            if ($this->isStrike($rollIndex)) {
                $totalScore += 10 + $this->strikeBonus($rollIndex);
                $rollIndex++;
            } else {

                // Spares are not considered in this game as stated in instructions

                $totalScore += $this->getDefaultFrameScore($rollIndex);
                $rollIndex += 2;
            }

            $scoreHistory[] = $totalScore;
        }

        return $scoreHistory;
    }

    private function isStrike(int $rollIndex): bool
    {
        return $this->rolls[$rollIndex] === 10;
    }

    private function strikeBonus(int $rollIndex): int
    {
        return $this->rolls[$rollIndex + 1] + $this->rolls[$rollIndex + 2];
    }

    /**
     * Return default sum of the two rolls for a frame.
     *
     * @param $rollIndex
     * @return int
     */
    private function getDefaultFrameScore(int $rollIndex): int
    {
        return $this->rolls[$rollIndex] + $this->rolls[$rollIndex + 1];
    }


    private function validateInputFrames(array $frames)
    {

        // we can split each check to a different private method if it gets too huge and unmaintainable later.
        // We may be able to use Laravel Validators here but decided not to.

        if (count($frames) !== 10) {
            throw new InvalidArgumentException('At least 10 input frames required');
        }

        $lastFrame = end($frames);

        foreach ($frames as $frame) {
            if (is_array($frame) === false) {
                throw new InvalidArgumentException('Each frame need to be an array too');
            }

            foreach ($frame as $num) {
                if (is_int($num) === false || $num < 0 || $num > 10) {
                    throw new InvalidArgumentException('The value in each frame should be an integer between 0 and 10 inclusive');
                }
            }


            if ($frame !== $lastFrame) {
                if (count($frame) > 2) {
                    throw new InvalidArgumentException('1st to 9th frame cannot exceed 2 rolls');
                }

                if ($frame[0] === 10 && count($frame) > 1) {
                    throw new InvalidArgumentException("If 1st roll in a non last frame is 10, there shouldn't be another roll in the same frame");
                }

                if (array_sum($frame) > 10) {
                    throw new InvalidArgumentException("Each frame sum before the last shouldn't exceed 10");
                }
            }

            // else part ($frame === $lastFrame)
            if (count($frame) > 3) {
                throw new InvalidArgumentException('The final frame (10th) cannot exceed 3 rolls');
            }

            if ($frame[0] !== 10 && count($frame) > 2) {
                throw new InvalidArgumentException('The final frame (10th) cannot exceed 2 rolls without at least 1 strike');
            }

        }
    }
}
