<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function getInputFramesDefault(): array
    {
        return [[5, 2], [8, 1], [6, 4], [10], [0, 5], [2, 6], [8, 1], [5, 3], [6, 1], [10, 2, 6]];
    }

    protected function setInputFramesDefault()
    {
        $this->inputFrames = $this->getInputFramesDefault();
    }
}
