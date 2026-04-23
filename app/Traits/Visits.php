<?php

namespace App\Traits;

trait Visits
{
    public function vzt(): \Awssat\Visits\Visits
    {
        return visits($this);
    }
}
