<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Icon;

class IconValidationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that icon URL validation requires a valid URL
     *
     * @return void
     */
    public function test_add_icon_requires_valid_url()
    {
        // Create a user
        $user = User::factory()->create();

        // Try to add an icon with invalid URL
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconURL' => 'not-a-valid-url',
                'newIconName' => 'Test Icon',
            ]);

        // Should fail validation
        $response->assertSessionHasErrors('newIconURL');
    }

    /**
     * Test that icon URL validation requires URL field
     *
     * @return void
     */
    public function test_add_icon_requires_url_field()
    {
        // Create a user
        $user = User::factory()->create();

        // Try to add an icon without URL
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconName' => 'Test Icon',
            ]);

        // Should fail validation
        $response->assertSessionHasErrors('newIconURL');
    }

    /**
     * Test that icon name validation requires name field
     *
     * @return void
     */
    public function test_add_icon_requires_name_field()
    {
        // Create a user
        $user = User::factory()->create();

        // Try to add an icon without name
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconURL' => 'https://example.com/icon.png',
            ]);

        // Should fail validation
        $response->assertSessionHasErrors('newIconName');
    }

    /**
     * Test that icon can be added with valid data
     *
     * @return void
     */
    public function test_add_icon_with_valid_data()
    {
        // Create a user
        $user = User::factory()->create();

        // Add an icon with valid data
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconURL' => 'https://example.com/icon.png',
                'newIconName' => 'Test Icon',
            ]);

        // Should succeed
        $response->assertStatus(302);
        
        // Verify icon was created
        $this->assertDatabaseHas('icons', [
            'url' => 'https://example.com/icon.png',
            'name' => 'Test Icon',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that icon URL cannot exceed max length
     *
     * @return void
     */
    public function test_add_icon_url_max_length()
    {
        // Create a user
        $user = User::factory()->create();

        // Try to add an icon with URL exceeding 500 characters
        $longUrl = 'https://example.com/' . str_repeat('a', 500);
        
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconURL' => $longUrl,
                'newIconName' => 'Test Icon',
            ]);

        // Should fail validation
        $response->assertSessionHasErrors('newIconURL');
    }

    /**
     * Test that icon name cannot exceed max length
     *
     * @return void
     */
    public function test_add_icon_name_max_length()
    {
        // Create a user
        $user = User::factory()->create();

        // Try to add an icon with name exceeding 255 characters
        $longName = str_repeat('a', 256);
        
        $response = $this->actingAs($user)
            ->post(route('addNewIcon'), [
                'newIconURL' => 'https://example.com/icon.png',
                'newIconName' => $longName,
            ]);

        // Should fail validation
        $response->assertSessionHasErrors('newIconName');
    }

    /**
     * Test that unauthenticated user cannot add icon
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_add_icon()
    {
        // Try to add an icon without authentication
        $response = $this->post(route('addNewIcon'), [
            'newIconURL' => 'https://example.com/icon.png',
            'newIconName' => 'Test Icon',
        ]);

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }
}
