@extends('layouts.app')

@section('title', 'Products')

@section('content')
  <div class="space-y-4">
    {{-- Header halaman --}}
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold text-gray-900">Products</h2>
        <p class="text-sm text-gray-500">Daftar produk yang tersedia.</p>
      </div>

      <a
        href="{{ route('products.create') }}"
        class="inline-flex items-center rounded-xl bg-indigo-600 text-white px-4 py-2.5 text-sm font-semibold
               hover:bg-indigo-700 active:bg-indigo-800 transition"
      >
        Tambah Produk
      </a>
    </div>

    {{-- Alert sukses --}}
    @if (session('status'))
      <div class="rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
        {{ session('status') }}
      </div>
    @endif

    {{-- TABEL SEDERHANA, FULL LEBAR --}}
    <div class="overflow-x-auto">
      <table class="w-full text-sm border border-gray-200 bg-white">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2 text-left font-semibold text-gray-600 border-b">Nama</th>
            <th class="px-3 py-2 text-left font-semibold text-gray-600 border-b">SKU</th>
            <th class="px-3 py-2 text-right font-semibold text-gray-600 border-b">Harga</th>
            <th class="px-3 py-2 text-right font-semibold text-gray-600 border-b">Stok</th>
            <th class="px-3 py-2 text-right font-semibold text-gray-600 border-b">Aksi</th>
          </tr>
        </thead>

        @if ($products->count())
          <tbody>
            @foreach ($products as $product)
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2">
                  <div class="font-medium text-gray-900">{{ $product->name }}</div>
                  @if ($product->description)
                    <div class="text-xs text-gray-400">
                      {{ $product->description }}
                    </div>
                  @endif
                </td>
                <td class="px-3 py-2 text-gray-700">
                  {{ $product->sku ?? '-' }}
                </td>
                <td class="px-3 py-2 text-right text-gray-900">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="px-3 py-2 text-right text-gray-900">
                  {{ $product->stock }} pcs
                </td>
                <td class="px-3 py-2">
                  <div class="flex justify-end gap-2 text-xs">
                    <a
                      href="{{ route('products.show', $product) }}"
                      class="text-indigo-600 hover:underline"
                    >
                      Lihat
                    </a>
                    <a
                      href="{{ route('products.edit', $product) }}"
                      class="text-blue-600 hover:underline"
                    >
                      Edit
                    </a>
                    <form
                      method="POST"
                      action="{{ route('products.destroy', $product) }}"
                      onsubmit="return confirm('Yakin ingin menghapus produk ini?');"
                    >
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:underline">
                        Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        @else
          <tbody>
            <tr>
              <td colspan="5" class="px-3 py-6 text-center text-gray-500">
                Belum ada produk. Klik <span class="font-semibold">Tambah Produk</span> untuk menambahkan.
              </td>
            </tr>
          </tbody>
        @endif
      </table>
    </div>

    {{-- Pagination --}}
    <div>
      {{ $products->links() }}
    </div>
  </div>
@endsection
