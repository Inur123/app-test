@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
  <div class=" mx-auto">
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-800">Tambah Produk</h2>
      <p class="text-sm text-gray-500 mt-1">Isi data produk baru.</p>

      @if ($errors->any())
        <div class="mt-4 mb-2 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
          <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('products.store') }}" class="mt-6 space-y-4">
        @csrf

        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
          <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">SKU (opsional)</label>
          <input
            type="text"
            name="sku"
            value="{{ old('sku') }}"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Harga</label>
            <input
              type="number"
              step="0.01"
              name="price"
              value="{{ old('price', 0) }}"
              required
              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                     focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Stok</label>
            <input
              type="number"
              name="stock"
              value="{{ old('stock', 0) }}"
              required
              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                     focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
          <textarea
            name="description"
            rows="4"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
          <a
            href="{{ route('products.index') }}"
            class="text-sm text-gray-600 hover:text-gray-800"
          >
            Batal
          </a>
          <button
            type="submit"
            class="rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium
                   hover:bg-indigo-700 active:bg-indigo-800 transition"
          >
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
