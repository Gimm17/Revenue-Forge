<?php

namespace Tests\Feature;

use App\Data\Enums\OfferType;
use App\Models\Offer;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoldenPathTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_owner_can_create_and_publish_offer(): void
    {
        // 1. Create User and Workspace
        $user = User::factory()->create();
        $workspace = Workspace::create([
            'name' => 'Golden Test',
            'slug' => 'golden-test',
            'owner_id' => $user->id
        ]);
        $user->workspaceMemberships()->create([
            'workspace_id' => $workspace->id,
            'role' => 'owner',
            'is_active' => true,
        ]);
        $user->switchWorkspace($workspace);

        // 2. Login
        $response = $this->actingAs($user)->get('/app');
        $response->assertStatus(200);

        // 3. Create Offer
        $offerData = [
            'title' => 'Test Golden Offer',
            'type' => OfferType::OneTime->value,
            'price' => 100000,
            'currency' => 'IDR',
        ];

        $response = $this->post('/app/offers', $offerData);
        $response->assertRedirect(); // Should redirect to edit page

        $offer = Offer::where('title', 'Test Golden Offer')->first();
        $this->assertNotNull($offer);
        $this->assertEquals(OfferType::OneTime, $offer->type);
        $this->assertFalse($offer->is_published);

        // 4. Publish Offer
        $response = $this->post('/app/offers/' . $offer->id . '/publish');
        $response->assertRedirect(); // Should redirect back

        $offer->refresh();
        $this->assertTrue($offer->is_published);

        // 5. View Public Offer Page
        $response = $this->get('/o/' . $offer->slug);
        $response->assertStatus(200);
        $response->assertSee('Test Golden Offer');
    }
}
