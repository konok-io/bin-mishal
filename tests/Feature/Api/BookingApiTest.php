<?php

namespace Tests\Feature\Api;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create(['user_id' => $this->user->id]);
        
        Branch::factory()->create();
    }

    public function test_can_list_bookings(): void
    {
        Booking::factory()->count(5)->create(['customer_id' => $this->customer->id]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->getJson('/api/v1/bookings');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_booking(): void
    {
        $response = $this->actingAs($this->admin, 'api')
            ->postJson('/api/v1/bookings', [
                'customer_id' => $this->customer->id,
                'booking_type' => 'flight',
                'service_type' => 'domestic',
                'trip_type' => 'oneway',
                'departure_date' => now()->addDays(30)->toDateString(),
                'departure_city' => 'Riyadh',
                'destination_city' => 'Jeddah',
                'adults' => 2,
                'children' => 1,
            ]);

        $response->assertCreated()
            ->assertJsonFragment(['booking_type' => 'flight']);
        
        $this->assertDatabaseHas('bookings', ['booking_type' => 'flight']);
    }

    public function test_can_view_booking(): void
    {
        $booking = Booking::factory()->create(['customer_id' => $this->customer->id]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->getJson("/api/v1/bookings/{$booking->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $booking->id]);
    }

    public function test_can_update_booking(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'booking_status' => 'pending',
        ]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->putJson("/api/v1/bookings/{$booking->id}", [
                'booking_status' => 'confirmed',
            ]);

        $response->assertOk()
            ->assertJsonFragment(['booking_status' => 'confirmed']);
    }

    public function test_can_delete_booking(): void
    {
        $booking = Booking::factory()->create(['customer_id' => $this->customer->id]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->deleteJson("/api/v1/bookings/{$booking->id}");

        $response->assertOk();
        $this->assertSoftDeleted('bookings', ['id' => $booking->id]);
    }

    public function test_unauthorized_user_cannot_access(): void
    {
        $booking = Booking::factory()->create(['customer_id' => $this->customer->id]);
        
        $response = $this->getJson("/api/v1/bookings/{$booking->id}");

        $response->assertUnauthorized();
    }
}
