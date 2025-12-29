@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
  <div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
      <p class="text-sm text-gray-500 mt-1">{{ $product->sku ?? 'SKU tidak ada' }}</p>

      <dl class="mt-4 space-y-3 text-sm">
        <div class="flex justify-between">
          <dt class="text-gray-500">Harga</dt>
          <dd class="font-medium text-gray-800">
            Rp {{ number_format($product->price, 0, ',', '.') }}
          </dd>
        </div>
        <div class="flex justify-between">
          <dt class="text-gray-500">Stok</dt>
          <dd class="font-medium text-gray-800">{{ $product->stock }}</dd>
        </div>
        <div>
          <dt class="text-gray-500 mb-1">Deskripsi</dt>
          <dd class="text-gray-800">
            {{ $product->description ?: '-' }}
          </dd>
        </div>
      </dl>

      <div class="mt-6 flex items-center justify-between">
        <a
          href="{{ route('products.index') }}"
          class="text-sm text-gray-600 hover:text-gray-800"
        >
          &larr; Kembali ke daftar
        </a>
        <a
          href="{{ route('products.edit', $product) }}"
          class="rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium
                 hover:bg-indigo-700 active:bg-indigo-800 transition"
        >
          Edit Produk
        </a>
      </div>
    </div>
  </div>
@endsection
