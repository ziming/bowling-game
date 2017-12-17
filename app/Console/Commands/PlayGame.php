<?php

namespace App\Console\Commands;

use App\BowlingGame;
use Exception;
use Illuminate\Console\Command;

class PlayGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bowling:play 
                            {frames : an array of array of 10 frames. E.g. [[5,2],[8,1],[6,4],[10],[0,5],[2,6],[8,1],[5,3],[6,1],[10,2,6]]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play a round of 10 pins, 10 frames bowling';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Welcome to Console Bowling Game!');
        $this->info('Taking your input to return your score history!');
        $this->info('If any error or exception occurs please fix your argument and run the command again');
        $inputFrames = $this->argument('frames');

        try {

            $inputFrames = json_decode($inputFrames);

            $scoreHistory = (new BowlingGame)->setInputFrames($inputFrames)->getScoreHistory();


            $scoreHistory = json_encode($scoreHistory);

            $this->info("The Score History is: {$scoreHistory}");

        } catch (Exception $e) {
            $exceptionClass = get_class($e);
            $this->warn("{$exceptionClass}: {$e->getMessage()}"); // error returns red box hard to see.
            $this->info('Try to fix your argument and run the command again.');
        }


    }
}
