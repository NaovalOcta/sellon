<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @livewireStyles
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/utils.js',
        'resources/js/layouts/base_js.js',
        'resources/js/products/product_feature_js.js',
        'resources/js/products/product_catalog_js.js',
    ])
</head>

<body>
    <div class="bg-brand-secondary text-slate-800 antialiased font-sans">
        <div id="toast-container"></div>

        @include('partials._nav')
        @include('partials._email_verification_banner')
        @yield('content')
        @include('partials._footer')
    </div>

    @livewireScripts

    <script>
        function showSessionToasts() {
            if (typeof window.triggerToast === 'undefined') return;
            @if (session('toast_error'))
                window.triggerToast("error", "{{ session('toast_error') }}");
            @endif
            @if (session('toast_success'))
                window.triggerToast('success', "{{ session('toast_success') }}");
            @endif
        }

        // Guard: pastikan listener hanya didaftarkan SATU KALI seumur hidup halaman.
        // Tanpa guard ini, setiap render Livewire SPA menambah listener baru
        // sehingga notifikasi muncul berulang kali saat back/forward.
        if (!window._livewireNavigatedToastRegistered) {
            window._livewireNavigatedToastRegistered = true;
            document.addEventListener('livewire:navigated', function () {
                setTimeout(showSessionToasts, 50);
            });
        }
    </script>
</body>

</html>
