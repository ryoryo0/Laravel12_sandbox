<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                background-color: rgb(249 250 251);
                border: 1px solid rgb(209 213 219);
                border-radius: 0.5rem;
                height: 42px;
                padding: 0.625rem;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: rgb(17 24 39);
                line-height: 1.5rem;
                padding-left: 0;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px;
            }
            .select2-dropdown {
                border: 1px solid rgb(209 213 219);
                border-radius: 0.5rem;
            }
            .select2-container--default .select2-search--dropdown .select2-search__field {
                border: 1px solid rgb(209 213 219);
                border-radius: 0.5rem;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: rgb(37 99 235);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('admin.layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @stack('scripts')
    </body>
</html>
