<?php

namespace Tests\Feature;

use App\BowlingGame;
use InvalidArgumentException;
use Tests\TestCase;

class SetInputFramesTest extends TestCase
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
        $this->bowlingGame = new BowlingGame;
        $this->setInputFramesDefault();
    }

    public function test_fail_if_not_10_frames()
    {
        $frames = array_slice($this->inputFrames, 0, rand(1, 9));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least 10 input frames required');

        $this->bowlingGame->setInputFrames($frames);
    }

    public function test_fail_if_1_frame_is_not_array()
    {
        $this->inputFrames[array_rand($this->inputFrames)] = 'Hello';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each frame need to be an array too');

        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_a_frame_value_is_not_an_int_between_0_to_10_inclusive()
    {

        // test above 10
        $this->inputFrames[array_rand($this->inputFrames)][0] = 11;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value in each frame should be an integer between 0 and 10 inclusive');

        $this->bowlingGame->setInputFrames($this->inputFrames);

        // test below 0
        $this->setInputFramesDefault();

        $this->inputFrames[array_rand($this->inputFrames)][0] = -1;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value in each frame should be an integer between 0 and 10 inclusive');

        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_1st_to_9th_frame_has_more_than_2_throws()
    {
        $this->inputFrames[rand(0, 8)] = [2, 7, 1];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('1st to 9th frame cannot exceed 2 rolls');
        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_last_frame_has_more_than_3_throws()
    {
        $this->inputFrames[count($this->inputFrames) - 1] = [10, 2, 6, 5];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The final frame (10th) cannot exceed 3 rolls');


        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_frame_rolls_is_more_than_1_when_there_is_a_strike_before_last_frame()
    {
        $this->inputFrames[rand(0, 8)] = [10, 0];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("If 1st roll in a non last frame is 10, there shouldn't be another roll in the same frame");

        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_non_last_frame_sum_is_more_than_10()
    {
        $this->inputFrames[rand(0, 8)] = [2, 9];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Each frame sum before the last shouldn't exceed 10");

        $this->bowlingGame->setInputFrames($this->inputFrames);
    }

    public function test_fail_if_last_frame_has_more_than_2_rolls_when_there_is_no_strike() {
        $this->inputFrames[9] = [2, 7, 1];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The final frame (10th) cannot exceed 2 rolls without at least 1 strike');

        $this->bowlingGame->setInputFrames($this->inputFrames);


    }



}
