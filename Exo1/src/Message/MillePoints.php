<?php

namespace App\Message;

final class MillePoints
{
    private int $points;

    public function __construct(int $points)
    {
        $this->points = $points;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

}
