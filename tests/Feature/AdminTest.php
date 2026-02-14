<?php

use App\Models\User;

function createAdmin(): User
{
    return User::factory()->create(['id' => 1]);
}

function createRegularUser(): User
{
    return User::factory()->create();
}

test('guests cannot access admin page', function () {
    $this->get('/admin')
        ->assertNotFound();
});

test('regular users cannot access admin page', function () {
    createAdmin();
    $user = createRegularUser();

    $this->actingAs($user)
        ->get('/admin')
        ->assertNotFound();
});

test('admin can access admin page', function () {
    $admin = createAdmin();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin Panel');
});

test('admin can search users', function () {
    $admin = createAdmin();
    User::factory()->create(['name' => 'Findable User', 'email' => 'findable@test.com']);
    User::factory()->create(['name' => 'Other Person', 'email' => 'other@test.com']);

    $this->actingAs($admin)
        ->get('/admin?search=Findable')
        ->assertOk()
        ->assertSee('Findable User')
        ->assertDontSee('Other Person');
});

test('admin can delete a user', function () {
    $admin = createAdmin();
    $user = User::factory()->create(['name' => 'Doomed User']);

    $this->actingAs($admin)
        ->delete(route('admin.deleteUser', $user->id))
        ->assertRedirect();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('admin cannot delete themselves', function () {
    $admin = createAdmin();

    $this->actingAs($admin)
        ->delete(route('admin.deleteUser', $admin->id))
        ->assertRedirect();

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin can stealth as another user', function () {
    $admin = createAdmin();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('stealth', $user->id))
        ->assertRedirect();

    $this->assertAuthenticatedAs($user);
});

test('admin can unstealth back', function () {
    $admin = createAdmin();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->withSession(['stealth' => $admin->id])
        ->get(route('unstealth'))
        ->assertRedirect();

    $this->assertAuthenticatedAs($admin);
});

test('regular user cannot delete users', function () {
    createAdmin();
    $user = createRegularUser();
    $other = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.deleteUser', $other->id))
        ->assertNotFound();

    $this->assertDatabaseHas('users', ['id' => $other->id]);
});
