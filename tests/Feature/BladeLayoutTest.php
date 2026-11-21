<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard shows admin navigation links', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Kategori')
        ->assertSee('Produk')
        ->assertSee('Laporan')
        ->assertSee('Transaksi');
});

test('dashboard shows only cashier navigation links for cashier', function () {
    $cashier = User::factory()->create(['role' => 'kasir']);

    $this->actingAs($cashier)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee('Transaksi')
        ->assertDontSee('Kategori')
        ->assertDontSee('Produk')
        ->assertDontSee('Laporan');
});
