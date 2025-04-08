<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->name }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Menyembunyikan scrollbar di seluruh halaman */
        html,
        body {
            overflow: hidden;
            /* Menyembunyikan scrollbar */
        }

        body {
            overflow-y: scroll;
            /* Pastikan scroll tetap aktif */
        }

        /* Untuk Chrome, Safari, dan Opera */
        body::-webkit-scrollbar {
            display: none;
            /* Menyembunyikan scrollbar */
        }

        /* Untuk Firefox */
        body {
            scrollbar-width: none;
            /* Menyembunyikan scrollbar */
        }

        /* Kelas untuk elemen yang dapat di-scroll */
        .scrollable {
            overflow-y: auto;
            /* Mengizinkan scroll vertikal */
            height: 100vh;
            /* Atur tinggi sesuai kebutuhan */
        }

        /* Untuk Chrome, Safari, dan Opera */
        .scrollable::-webkit-scrollbar {
            display: none;
            /* Menyembunyikan scrollbar */
        }

        /* Untuk Firefox */
        .scrollable {
            scrollbar-width: none;
            /* Menyembunyikan scrollbar */
        }
    </style>
</head>

<body class="scrollable bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                            {{ Auth::user()->name }}
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Desktop Navbar -->
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('transaksi.index') }}"
                            class="{{ request()->routeIs('transaksi.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Transaksi
                        </a>
                        <a href="{{ route('pelanggan.index') }}"
                            class="{{ request()->routeIs('pelanggan.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Pelanggan
                        </a>
                        <a href="{{ route('layanan.index') }}"
                            class="{{ request()->routeIs('layanan.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Layanan
                        </a>
                        <a href="{{ route('pengeluaran.index') }}"
                            class="{{ request()->routeIs('pengeluaran.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Pengeluaran
                        </a>
                    </div>
                </div>

                <!-- Mobile Hamburger Menu -->
                <div class="flex items-center sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 focus:outline-none">
                        <i :class="open ? 'fa fa-times' : 'fa fa-bars'" class="text-xl"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute top-16 left-0 w-full bg-white shadow-lg z-50">
                        <nav class="flex flex-col space-y-1 p-4">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Dashboard
                            </a>
                            <a href="{{ route('transaksi.index') }}"
                                class="{{ request()->routeIs('transaksi.*') ? 'bg-indigo-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Transaksi
                            </a>
                            <a href="{{ route('pelanggan.index') }}"
                                class="{{ request()->routeIs('pelanggan.*') ? 'bg-indigo-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Pelanggan
                            </a>
                            <a href="{{ route('layanan.index') }}"
                                class="{{ request()->routeIs('layanan.*') ? 'bg-indigo-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Layanan
                            </a>
                            <a href="{{ route('pengeluaran.index') }}"
                                class="{{ request()->routeIs('pengeluaran.*') ? 'bg-indigo-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Pengeluaran
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="text-gray-600 hover:bg-gray-100 block px-4 py-2 rounded-md">
                                Akun
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="text-gray-600 hover:bg-gray-100 block w-full text-left px-4 py-2 rounded-md">
                                    Log Out
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                <!-- Profile dropdown for Desktop -->
                <div class="hidden sm:flex items-center">
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open"
                                class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                    class="h-8 w-auto rounded-full">
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100"
                            role="menu">
                            <div class="py-1" role="none">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Akun</a>
                            </div>
                            <div class="py-1" role="none">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main >
        <div class="container mx-auto mt-6 p-4 ">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>
