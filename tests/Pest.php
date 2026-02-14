<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extends(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extends(TestCase::class)
    ->in('Unit');
