<?php

namespace Tests\Feature;

use App\BowlingGame;
use Tests\TestCase;

class ViewScoreHistoryTest extends TestCase
{
    /**
     * @var array
     */
    protected $inputFrames;

    /**
     * @var BowlingGame
     */
    protected $bowlingGame;

    public function setUp()
    {
        parent::setUp();
        $this->bowlingGame = new BowlingGame();
        $this->setInputFramesDefault();
    }

    public function test_all_score_history_entries_are_0_for_gutter_game()
    {
        $frames = array_fill(0, BowlingGame::FRAMES_PER_GAME, [0, 0]);

        $this->bowlingGame->setInputFrames($frames);

        $expectedArray = array_fill(0, BowlingGame::FRAMES_PER_GAME, 0);

        $this->assertEquals($expectedArray, $this->bowlingGame->getScoreHistory());
    }

    public function test_score_history_correct_when_no_frames_has_strike_nor_spare()
    {
        $frames = [];
        $expectedScoreHistory = [];
        $prevFrameScore = 0;

        for ($i = 0; $i < BowlingGame::FRAMES_PER_GAME; $i++) {
            $firstRoll = rand(0, 9);
            $secondRoll = 9 - $firstRoll;

            $frames[] = [$firstRoll, $secondRoll];
            $expectedScoreHistory[] = 9 + $prevFrameScore;
            $prevFrameScore = $expectedScoreHistory[$i];
        }

        $this->bowlingGame->setInputFrames($frames);


        $this->assertEquals($expectedScoreHistory, $this->bowlingGame->getScoreHistory());
    }

    public function test_score_history_correct_with_question_input_and_expected_output()
    {
        $this->bowlingGame->setInputFrames($this->inputFrames);

        $expectedOutput = [7, 16, 26, 41, 46, 54, 63, 71, 78, 96];
        $this->assertEquals($expectedOutput, $this->bowlingGame->getScoreHistory());
    }

    public function test_score_history_final_score_is_300_in_a_perfect_game()
    {
        $frames = array_fill(0, BowlingGame::FRAMES_PER_GAME - 1, [10]);

        // The final frame
        $frames[] = [10, 10, 10];

        $this->bowlingGame->setInputFrames($frames);

        $expectedScoreHistory = [30, 60, 90, 120, 150, 180, 210, 240, 270, 300];
        $actualScoreHistory = $this->bowlingGame->getScoreHistory();

        $this->assertEquals($expectedScoreHistory, $actualScoreHistory);
    }

    public function test_score_history_final_score_is_20_when_all_i_rolled_is_1()
    {
        $frames = array_fill(0, BowlingGame::FRAMES_PER_GAME, [1, 1]);


        $this->bowlingGame->setInputFrames($frames);

        $expectedScoreHistory = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20];
        $actualScoreHistory = $this->bowlingGame->getScoreHistory();

        $this->assertEquals($expectedScoreHistory, $actualScoreHistory);
    }


    private function getInputFramesDefault(): array
    {
        // note: copy and pasted from the other test class.
        return [[5, 2], [8, 1], [6, 4], [10], [0, 5], [2, 6], [8, 1], [5, 3], [6, 1], [10, 2, 6]];
    }

    private function setInputFramesDefault()
    {
        // note: copy and pasted from the other test class.
        $this->inputFrames = $this->getInputFramesDefault();
    }
}
