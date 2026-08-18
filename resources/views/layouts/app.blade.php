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

            <!-- Main Content Body -->
            <main class="p-6 flex-1">
                {{-- Flash Message Success --}}
                @session('success')
                <div role="alert" class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-xs flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ $value }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-lg leading-none">&times;</button>
                </div>
                @endsession

                {{-- Flash Message Error --}}
                @session('error')
                <div role="alert" class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-r-lg shadow-xs flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ $value }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-lg leading-none">&times;</button>
                </div>
                @endsession

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

            // 2. Auto-close Alerts after 4s
            const alertElements = document.querySelectorAll('[role="alert"]');
            alertElements.forEach(function(alert) {
                setTimeout(function() {
                    alert.remove();
                }, 4000);
            });

            // 3. Global SweetAlert Confirm Delete Delegation
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