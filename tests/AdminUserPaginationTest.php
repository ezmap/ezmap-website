<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class AdminUserPaginationTest extends TestCase
{
    use DatabaseTransactions;

    public function testAdminPagePaginatesUsers()
    {
        // Create admin user (ID 1)
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create multiple test users (more than default page size)
        for ($i = 1; $i <= 200; $i++) {
            User::create([
                'name' => "Test User {$i}",
                'email' => "user{$i}@test.com",
                'password' => bcrypt('password'),
            ]);
        }

        // Act as admin and visit admin page
        $response = $this->actingAs($admin)
                         ->get('/admin')
                         ->assertStatus(200);

        // Check that pagination exists when there are many users
        $response->assertSee('Showing');
        $response->assertSee('of 21 users'); // 20 test users + 1 admin
    }

    public function testAdminPageSearchesByName()
    {
        // Create admin user
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create specific test users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@test.com',
            'password' => bcrypt('password'),
        ]);

        // Search for "John"
        $response = $this->actingAs($admin)
                         ->get('/admin?search=John')
                         ->assertStatus(200);

        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    public function testAdminPageSearchesByEmail()
    {
        // Create admin user
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create specific test users
        User::create([
            'name' => 'Test User',
            'email' => 'specific@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Another User',
            'email' => 'different@test.com',
            'password' => bcrypt('password'),
        ]);

        // Search for "specific@"
        $response = $this->actingAs($admin)
                         ->get('/admin?search=specific@')
                         ->assertStatus(200);

        $response->assertSee('specific@example.com');
        $response->assertDontSee('different@test.com');
    }

    public function testAdminPageShowsNoResultsMessage()
    {
        // Create admin user
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Search for non-existent user
        $response = $this->actingAs($admin)
                         ->get('/admin?search=nonexistent')
                         ->assertStatus(200);

        $response->assertSee('No users found matching "nonexistent"');
    }

    public function testNonAdminCannotAccessAdminPage()
    {
        // Create regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        // Try to access admin page as non-admin
        $this->actingAs($user)
             ->get('/admin')
             ->assertStatus(404); // Should be blocked by admin middleware
    }
}