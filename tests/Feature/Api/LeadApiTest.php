<?php

namespace Tests\Feature\Api;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $agent;
    private LeadSource $source;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->agent = User::factory()->create();
        $this->agent->assignRole('agent');
        
        $this->source = LeadSource::factory()->create();
    }

    public function test_can_list_leads(): void
    {
        Lead::factory()->count(5)->create(['source_id' => $this->source->id]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->getJson('/api/v1/leads');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_lead(): void
    {
        $response = $this->actingAs($this->agent, 'api')
            ->postJson('/api/v1/leads', [
                'name' => 'Ahmed Khan',
                'email' => 'ahmed@example.com',
                'phone' => '+966501234567',
                'source_id' => $this->source->id,
                'service_type' => 'umrah',
                'notes' => 'Interested in Umrah package',
            ]);

        $response->assertCreated()
            ->assertJsonFragment(['name' => 'Ahmed Khan']);
    }

    public function test_can_update_lead_status(): void
    {
        $lead = Lead::factory()->create([
            'source_id' => $this->source->id,
            'status' => 'new',
        ]);
        
        $response = $this->actingAs($this->agent, 'api')
            ->putJson("/api/v1/leads/{$lead->id}/status", [
                'status' => 'contacted',
                'notes' => 'Called and discussed requirements',
            ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 'contacted']);
    }

    public function test_can_assign_lead_to_agent(): void
    {
        $lead = Lead::factory()->create([
            'source_id' => $this->source->id,
            'assigned_to' => null,
        ]);
        
        $response = $this->actingAs($this->admin, 'api')
            ->putJson("/api/v1/leads/{$lead->id}/assign", [
                'user_id' => $this->agent->id,
            ]);

        $response->assertOk()
            ->assertJsonFragment(['assigned_to' => $this->agent->id]);
    }

    public function test_lead_generates_lead_no(): void
    {
        $response = $this->actingAs($this->agent, 'api')
            ->postJson('/api/v1/leads', [
                'name' => 'Test Lead',
                'email' => 'test@example.com',
                'phone' => '+966501234567',
                'source_id' => $this->source->id,
                'service_type' => 'flight',
            ]);

        $response->assertCreated();
        
        $lead = Lead::first();
        $this->assertNotNull($lead->lead_no);
        $this->assertStringStartsWith('LEAD-', $lead->lead_no);
    }
}
