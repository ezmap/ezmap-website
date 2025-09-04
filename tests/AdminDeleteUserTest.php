<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Map;
use App\Models\Icon;

class AdminDeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    public function testAdminCanDeleteUser()
    {
        // Create admin user (ID 1)
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com', 
            'password' => bcrypt('password'),
        ]);

        // Create a map for the user
        $map = Map::create([
            'user_id' => $user->id,
            'title' => 'Test Map',
            'latitude' => 0,
            'longitude' => 0,
            'markers' => '[]',
            'mapOptions' => '{}',
        ]);

        // Create an icon for the user
        $icon = Icon::create([
            'user_id' => $user->id,
            'url' => 'http://example.com/icon.png',
            'name' => 'Test Icon',
        ]);

        // Act as admin and delete the user
        $this->actingAs($admin)
             ->delete("/admin/deleteuser/{$user->id}")
             ->assertRedirect();

        // Assert user and related data are deleted
        $this->assertNull(User::find($user->id));
        $this->assertNull(Map::find($map->id)); 
        $this->assertNull(Icon::find($icon->id));
    }

    public function testCannotDeleteAdminAccount()
    {
        // Create admin user
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Try to delete admin account
        $this->actingAs($admin)
             ->delete("/admin/deleteuser/1")
             ->assertRedirect()
             ->assertSessionHas('error', 'Cannot delete the admin account.');

        // Assert admin still exists
        $this->assertNotNull(User::find(1));
    }

    public function testNonAdminCannotDeleteUser()
    {
        // Create regular user
        $user = User::create([
            'name' => 'Test User', 
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $userToDelete = User::create([
            'name' => 'User to Delete',
            'email' => 'delete@test.com',
            'password' => bcrypt('password'),
        ]);

        // Try to delete user as non-admin
        $this->actingAs($user)
             ->delete("/admin/deleteuser/{$userToDelete->id}")
             ->assertStatus(404); // Should be blocked by admin middleware

        // Assert user still exists
        $this->assertNotNull(User::find($userToDelete->id));
    }
}