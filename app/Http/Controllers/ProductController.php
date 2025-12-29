<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    // LIST product
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    // FORM TAMBAH
    public function create(): View
    {
        return view('products.create');
    }

    // SIMPAN PRODUK BARU
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['nullable', 'string', 'max:100'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil ditambahkan.');
    }

    // DETAIL / SHOW
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    // FORM EDIT
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    // UPDATE PRODUK
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['nullable', 'string', 'max:100'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil diperbarui.');
    }

    // HAPUS PRODUK
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }
}
