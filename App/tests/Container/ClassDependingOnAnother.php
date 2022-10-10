<?php

namespace Student\App\UnitTests\Container;

class ClassDependingOnAnother
{
    private SomeClassWithoutDependencies $one;
    private SomeClassWithParameter $two;

    public function __construct(SomeClassWithoutDependencies $one, SomeClassWithParameter $two)
    {
        $this->one = $one;
        $this->two = $two;
    }
}