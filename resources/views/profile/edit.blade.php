@extends('layouts.app')

@section('title', 'Profile')

@section('content')
  <div class="mx-auto">
    {{-- alert sukses --}}
    @if (session('status'))
      <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
        {{ session('status') }}
      </div>
    @endif

    {{-- error validasi --}}
    @if ($errors->any())
      <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
        <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-800">Edit Profile</h2>
      <p class="text-sm text-gray-500 mt-1">Ubah data akun kamu di sini.</p>

      <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
          <label class="block text-sm font-medium text-gray-700">Nama</label>
          <input
            type="text"
            name="name"
            value="{{ old('name', auth()->user()->name) }}"
            required
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            name="email"
            value="{{ old('email', auth()->user()->email) }}"
            required
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
        </div>

        <hr class="my-2">

        <p class="text-sm text-gray-500">
          Jika tidak ingin mengganti password, biarkan kolom di bawah ini kosong.
        </p>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password baru</label>
          <input
            type="password"
            name="password"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Minimal 8 karakter"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Konfirmasi password baru</label>
          <input
            type="password"
            name="password_confirmation"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
          <a
            href="{{ route('dashboard') }}"
            class="text-sm text-gray-600 hover:text-gray-800"
          >
            Batal
          </a>
          <button
            type="submit"
            class="rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium
                   hover:bg-indigo-700 active:bg-indigo-800 transition"
          >
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
