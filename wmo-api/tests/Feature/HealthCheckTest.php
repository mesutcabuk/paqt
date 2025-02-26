<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HealthCheckTest extends TestCase
{
    public function test_health_check_success()
    {
        Http::fake([
            'https://thirdparty.example.com/health' => Http::response(['status' => 'ok'], 200),
        ]);

        Log::shouldReceive('info')->once()->with("Health check success: https://thirdparty.example.com/health");

        $this->artisan('health:check')->assertExitCode(0);
    }

    public function test_health_check_failure()
    {
        Http::fake([
            'https://thirdparty.example.com/health' => Http::response([], 500),
        ]);

        Log::shouldReceive('error')->once();

        $this->artisan('health:check')->assertExitCode(1);
    }
}