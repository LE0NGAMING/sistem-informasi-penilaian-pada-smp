<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SIP SMP</title>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons (Hanya Icon) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-['Inter'] bg-slate-50 text-slate-700 antialiased min-h-screen">

    <div id="wrapper" class="flex min-h-screen overflow-x-hidden">
        <!-- Sidebar Navigation -->
        @include('layouts.partials.sidebar')

        <!-- Page Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar Header -->
            @include('layouts.partials.navbar')

            <!-- Main Content Body (Sudah diperbaiki) -->
            <main class="p-6 flex-1">

                <!-- Global Flash Message -->
                @if (session('success'))
                <x-alert type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                <x-alert type="error" :message="session('error')" />
                @endif

                {{-- Dynamic Page Content --}}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global UI Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Sidebar Toggle Logic
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar-wrapper');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('-ml-64');
                });
            }

            // (Skrip JS Auto-close Alert telah dihapus karena sudah ditangani Alpine.js di dalam komponen)

            // 2. Global SweetAlert Confirm Delete Delegation
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form && form.classList.contains('delete-form')) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>