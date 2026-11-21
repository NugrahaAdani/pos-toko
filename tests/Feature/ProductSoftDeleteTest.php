<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('soft deletes products instead of removing them from the database', function () {
    $category = Category::create(['name' => 'Kategori uji']);
    $product = Product::create([
        'category_id' => $category->id,
        'code' => 'SOFT-DELETE-1',
        'name' => 'Produk uji hapus',
        'unit' => 'pcs',
        'price' => 1000,
        'stock' => 5,
    ]);

    $product->delete();

    expect(class_uses_recursive(Product::class))
        ->toContain(SoftDeletes::class);
    expect($product->trashed())->toBeTrue();
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'deleted_at' => $product->deleted_at,
    ]);
    expect(Product::find($product->id))->toBeNull();
});
