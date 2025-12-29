<aside class="w-64 bg-white shadow-lg hidden md:flex md:flex-col">
  <div class="h-16 flex items-center px-6 border-b border-gray-300 border-r">
    <span class="font-semibold text-lg text-indigo-600">MyApp</span>
  </div>

  <nav class="flex-1 p-4 space-y-1">
    {{-- Dashboard --}}
    <a
      href="{{ route('dashboard') }}"
      class="block px-3 py-2 rounded-lg text-sm font-medium
             {{ request()->routeIs('dashboard')
                  ? 'bg-indigo-50 text-indigo-700'
                  : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}"
    >
      Dashboard
    </a>

    {{-- Profile --}}
    <a
      href="{{ route('profile.edit') }}"
      class="block px-3 py-2 rounded-lg text-sm font-medium
             {{ request()->routeIs('profile.*')
                  ? 'bg-indigo-50 text-indigo-700'
                  : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}"
    >
      Profile
    </a>

    {{-- Products --}}
    <a
      href="{{ route('products.index') }}"
      class="block px-3 py-2 rounded-lg text-sm font-medium
             {{ request()->routeIs('products.*')
                  ? 'bg-indigo-50 text-indigo-700'
                  : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}"
    >
      Products
    </a>
  </nav>
</aside>
