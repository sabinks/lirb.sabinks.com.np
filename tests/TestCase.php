<?php

namespace Tests;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function authUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }
}
