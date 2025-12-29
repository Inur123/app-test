@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="grid gap-6 md:grid-cols-2">
    {{-- Card profil (view only) --}}
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-base font-semibold text-gray-800">Profil</h2>
      <p class="text-sm text-gray-500 mt-1">Informasi akun kamu</p>

      <dl class="mt-4 space-y-3 text-sm">
        <div class="flex justify-between">
          <dt class="text-gray-500">Nama</dt>
          <dd class="font-medium text-gray-800">
            {{ auth()->user()->name }}
          </dd>
        </div>

        <div class="flex justify-between">
          <dt class="text-gray-500">Email</dt>
          <dd class="font-medium text-gray-800">
            {{ auth()->user()->email }}
          </dd>
        </div>
      </dl>
    </div>

    {{-- Card kosong untuk konten lain --}}
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-base font-semibold text-gray-800">Ringkasan</h2>
      <p class="text-sm text-gray-500 mt-1">
        Di sini nanti bisa diisi statistik, data, dll.
      </p>
    </div>
  </div>
@endsection
