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
    {{-- =====================================================
         MAZE HEATMAP & USER TESTING SNIPPET
         Ganti 'YOUR_MAZE_API_KEY' dengan API key dari:
         app.maze.co → Project → Install Snippet
         ===================================================== --}}
    <script>
        (function(m, a, z, e) {
            var s, t, u, v;
            try {
                t = m.sessionStorage.getItem('maze-us');
            } catch (err) {}

            if (!t) {
                t = new Date().getTime();
                try {
                    m.sessionStorage.setItem('maze-us', t);
                } catch (err) {}
            }

            u = document.currentScript || (function() {
                var w = document.getElementsByTagName('script');
                return w[w.length - 1];
            })();
            v = u && u.nonce;

            s = a.createElement('script');
            s.src = z + '?apiKey=' + e;
            s.async = true;
            if (v) s.setAttribute('nonce', v);
            a.getElementsByTagName('head')[0].appendChild(s);
            m.mazeUniversalSnippetApiKey = e;
        })(window, document, 'https://snippet.maze.co/maze-universal-loader.js', 'f39b0f2f-0ecd-46bf-8c31-3d69509718d7');
    </script>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/utils.js', 'resources/js/layouts/base_js.js', 'resources/js/products/product_feature_js.js', 'resources/js/products/product_catalog_js.js'])
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
            document.addEventListener('livewire:navigated', function() {
                setTimeout(showSessionToasts, 50);

                // ── Maze SPA Support ──────────────────────────────────────────
                // Livewire menggunakan soft-navigation (tanpa full page reload),
                // sehingga Maze tidak otomatis mendeteksi perpindahan halaman.
                // Kita beritahu Maze secara manual setiap kali halaman berganti
                // agar path steps pada Path-based heatmap terdeteksi dengan benar.
                setTimeout(function() {
                    if (typeof window.maze === 'function') {
                        window.maze('pageview');
                    }
                }, 100);
                // ─────────────────────────────────────────────────────────────
            });
        }
    </script>
</body>

</html>
