<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('PERSIAN_APP_NAME') . " | " . $title ?? env('APP_NAME') }}</title>

{{--    <link rel="icon" href="/build/assets/icons/favicon.ico" type="image/x-icon">--}}

    <!-- Scripts -->
    <link rel="stylesheet" href="/build/plugins/tagify/tagify.css">
    <script src="/build/plugins/tagify/tagify.min.js"></script>
    <script src="/build/plugins/tagify/tagify.polyfills.min.js"></script>

    <link rel="stylesheet" href="/build/plugins/jalali-datepicker/jalali-datepicker.min.css"/>
    <script src="/build/plugins/jalali-datepicker/jalali-datepicker.js"></script>

    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>--}}
    <x-head.tinymce-config/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <livewire:layout.navigation/>

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-primary dark:bg-gray-800 shadow">
            <div class=" mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <x-notification-modal name="success-notification">
        Operation Successful!
    </x-notification-modal>

    <x-flash-messages/>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
@livewireScripts
@filepondScripts
</body>
</html>
