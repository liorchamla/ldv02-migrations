<?php

namespace Graille\Migration;

interface MigrationInterface
{
    public function execute(Plan $plan);
}
