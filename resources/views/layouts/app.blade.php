<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard')</title>

  {{-- Tailwind via Vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
  <div class="min-h-screen flex bg-gray-100">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Konten utama --}}
    <div class="flex-1 flex flex-col">
      {{-- Header --}}
      @include('layouts.header')

      {{-- Isi halaman --}}
      <main class="flex-1 p-6">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
