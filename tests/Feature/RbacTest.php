<?php

use App\Models\User;
use Database\Seeders\RoleAndUserSeeder;

beforeEach(function () {
    $this->seed(RoleAndUserSeeder::class);
});

it('member tidak bisa akses halaman users', function () {
    $user = User::where('email', 'member@gmail.com')->first();

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertStatus(403);
});

it('staff tidak bisa akses halaman users', function () {
    $staff = User::where('email', 'staff@gmail.com')->first();

    $response = $this->actingAs($staff)->get(route('users.index'));

    $response->assertStatus(403);
});

it('admin bisa akses halaman users', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertStatus(200);
});

it('halaman dashboard bisa diakses semua role', function () {
    $admin = User::where('email', 'admin@gmail.com')->first();
    $staff = User::where('email', 'staff@gmail.com')->first();
    $member = User::where('email', 'member@gmail.com')->first();

    foreach ([$admin, $staff, $member] as $user) {
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
    }
});

it('halaman chat bisa diakses semua role', function () {
    $member = User::where('email', 'member@gmail.com')->first();

    $response = $this->actingAs($member)->get(route('chat.index'));

    $response->assertStatus(200);
});
