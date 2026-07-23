<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\User;
use App\Models\VisaApplication;
use App\Models\VisaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisaApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Customer $customer;
    private VisaType $visaType;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->customer = Customer::factory()->create();
        $this->visaType = VisaType::factory()->create();
    }

    public function test_can_list_visa_applications(): void
    {
        VisaApplication::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
            'visa_type_id' => $this->visaType->id,
        ]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->getJson('/api/v1/visas');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_visa_application(): void
    {
        $response = $this->actingAs($this->admin, 'api')
            ->postJson('/api/v1/visas', [
                'customer_id' => $this->customer->id,
                'visa_type_id' => $this->visaType->id,
                'applicant_name' => 'John Doe',
                'passport_no' => 'AB123456',
                'nationality' => 'Bangladeshi',
                'purpose' => 'Family Visit',
            ]);

        $response->assertCreated()
            ->assertJsonFragment(['applicant_name' => 'John Doe']);
    }

    public function test_can_update_visa_status(): void
    {
        $visa = VisaApplication::factory()->create([
            'customer_id' => $this->customer->id,
            'visa_type_id' => $this->visaType->id,
            'status' => 'submitted',
        ]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->putJson("/api/v1/visas/{$visa->id}/status", [
                'status' => 'under_review',
                'notes' => 'Documents verified',
            ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 'under_review']);
    }

    public function test_can_add_status_log(): void
    {
        $visa = VisaApplication::factory()->create([
            'customer_id' => $this->customer->id,
            'visa_type_id' => $this->visaType->id,
            'status' => 'draft',
        ]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->postJson("/api/v1/visas/{$visa->id}/status-log", [
                'status' => 'submitted',
                'notes' => 'Application submitted',
            ]);

        $response->assertCreated();
        
        $this->assertDatabaseHas('visa_status_logs', [
            'visa_application_id' => $visa->id,
            'status' => 'submitted',
        ]);
    }
}
