<?php 

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Resident;
use App\Models\Ride;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RideTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_ride_success()
    {
        $resident = Resident::factory()->create(['budget' => 100]);

        $response = $this->postJson('/api/rides/book', [
            'resident_id' => $resident->id,
            'pickup_address' => 'Test Address',
            'destination' => 'Another Address',
            'distance_km' => 50,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('rides', ['resident_id' => $resident->id]);
    }

    public function test_book_ride_insufficient_budget()
    {
        $resident = Resident::factory()->create(['budget' => 10]);

        $response = $this->postJson('/api/rides/book', [
            'resident_id' => $resident->id,
            'pickup_address' => 'Test Address',
            'destination' => 'Another Address',
            'distance_km' => 50,
        ]);

        $response->assertStatus(400);
    }
}