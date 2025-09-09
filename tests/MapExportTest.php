<?php

use App\Models\Map;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class MapExportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testKmlExportRequiresAuthentication()
    {
        $user = User::factory()->create();
        $map = Map::factory()->create(['user_id' => $user->id]);

        // Test unauthenticated access
        $response = $this->get(route('map.kml', $map));
        $response->assertRedirect(route('login'));
    }

    public function testKmzExportRequiresAuthentication()
    {
        $user = User::factory()->create();
        $map = Map::factory()->create(['user_id' => $user->id]);

        // Test unauthenticated access
        $response = $this->get(route('map.kmz', $map));
        $response->assertRedirect(route('login'));
    }

    public function testKmlExportForAuthenticatedUser()
    {
        $user = User::factory()->create();
        $map = Map::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Map',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'markers' => json_encode([
                (object)[
                    'title' => 'Test Marker',
                    'lat' => 40.7128,
                    'lng' => -74.0060,
                    'icon' => 'https://example.com/icon.png',
                    'infoWindow' => (object)['content' => 'Test marker content']
                ]
            ])
        ]);

        $response = $this->actingAs($user)->get(route('map.kml', $map));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.google-earth.kml+xml');
        
        $content = $response->getContent();
        $this->assertStringContainsString('<?xml', $content);
        $this->assertStringContainsString('<kml', $content);
        $this->assertStringContainsString('Test Map', $content);
        $this->assertStringContainsString('Test Marker', $content);
        $this->assertStringContainsString('-74.0060,40.7128', $content);
    }

    public function testKmzExportForAuthenticatedUser()
    {
        $user = User::factory()->create();
        $map = Map::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Map KMZ',
            'latitude' => 40.7128,
            'longitude' => -74.0060
        ]);

        $response = $this->actingAs($user)->get(route('map.kmz', $map));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="test-map-kmz.kmz"');
    }

    public function testUserCannotExportOtherUsersMap()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $map = Map::factory()->create(['user_id' => $user1->id]);

        // User 2 trying to access User 1's map
        $response = $this->actingAs($user2)->get(route('map.kml', $map));
        $response->assertStatus(403);

        $response = $this->actingAs($user2)->get(route('map.kmz', $map));
        $response->assertStatus(403);
    }
}