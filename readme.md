# Bowling Game Test

## Setup
- Just make sure PHP 7 is installed. 
- To be safe be on PHP 7.1.12 or above since that's the version on my machine
- Make sure composer is installed and run `composer install` in project directory

## Example Commands

```
php artisan bowling:play "[[5,2],[8,1],[6,4],[10],[0,5],[2,6],[8,1],[5,3],[6,1],[10,2,6]]"
php artisan bowling:play "[[10],[10],[10],[10],[10],[10],[10],[10],[10],[10,10,10]]"
php artisan bowling:play "[[1,1],[1,1],[1,1],[1,1],[1,1],[1,1],[1,1],[1,1],[1,1],[1,1]]"

php artisan help bowling:play

```

## Running the tests

```
phpunit

phpunit --testdox
```

## Main Files Location

```
app/Console/Commands/PlayGame.php
app/BowlingGame.php
tests/Features/SetInputFramesTest.php
tests/Features/ViewScoreHistoryTest.php
```

## Requirements
This test is to write a program for a ten-pin bowling game.

A bowler has ten frames to knock down pins. In each of 1 to 9 frames, the bowler makes 1 or 2 throws while the tenth 
frame has up to three throws.

You program should be able to receive an array of arrays of pins knocked down by each throw as an
input. Each number should always be an integer between 0 and 10. Each sub-array represents one
frame of a game.

The basic rules of this bowling game are:

- No additional score for a spare.

-  If a bowler knocks down all ten pins on the first throw, it’s a strike. A strike is worth 10, plus
the score in the next frame if the next frame is not a strike. If the next frame is also a strike,
which means the bowler has made two strikes, then the score from the third frame is also
added to the first frame.

Ex. 10 (first frame) +10 (second frame) +10 (third frame) results in the first frame with a score
of 30.

Ex. 10 (first frame) +6 (second frame) results in the first frame with a score of 16.

- If a strike is not made in a frame, the score of the frame is just the sum of pins knocked
down.

- The last frame has up to three throws. If a bowler had a strike on the first throw, the bowler is
allowed to have one additional throw. So the bowler has three throws in total. If the bowler
made two strikes he will still be able to do the last throw

Your program should display a list of integers representing the total scores at the end of each frame.

Example:

Input: [[5,2],[8,1],[6,4],[10],[0,5],[2,6],[8,1],[5,3],[6,1],[10,2,6]]
Output: [7, 16, 26, 41, 46, 54, 63, 71, 78, 96]

As long as the above basic rules of this bowling games are followed, the functionality is ok even if it
differs from typical rules of a bowling game.
