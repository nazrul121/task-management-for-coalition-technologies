<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NazrulIslam test | Task Manager')</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Styling the Search and Length Menu wrappers */
        .dataTables_wrapper .dataTables_length select {
            @apply bg-white border border-slate-200 rounded-lg px-3 py-1 outline-none focus:ring-2 focus:ring-blue-500;
            min-width: 60px;
        }
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-white border border-slate-200 rounded-lg px-4 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 transition-all;
        }
        /* Fixing the sorting arrows */
        table.dataTable thead th { position: relative; cursor: pointer; }
        table.dataTable thead th.sorting:after { content: "↕"; position: absolute; right: 10px; opacity: 0.2; }
        table.dataTable thead th.sorting_asc:after { content: "↑"; position: absolute; right: 10px; opacity: 1; color: #2563eb; }
        table.dataTable thead th.sorting_desc:after { content: "↓"; position: absolute; right: 10px; opacity: 1; color: #2563eb; }
        .paginate_button {padding:4px; cursor: pointer;}

        /* Styling the DataTables Processing Overlay */
        #task-table_wrapper {
            position: relative;
        }

        /* The Overlay / Loader Container */
        div.dataTables_wrapper div.dataTables_processing {
            @apply absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                bg-white/90 backdrop-blur-sm border border-slate-200
                rounded-2xl px-10 py-6 shadow-2xl z-50 !important;
            margin: 0 !important;
            width: auto !important;
        }

        /* Blur the table content when processing */
        .dataTables_wrapper.processing #task-table {
            @apply blur-[2px] opacity-60 transition-all duration-300;
        }

        /* Spinner Animation */
        .loading-spinner {
            @apply w-8 h-8 border-[3px] border-slate-100 border-t-blue-600
                rounded-full animate-spin mb-2;
        }


        /* Custom Premium Sorting Icons */
        table.dataTable thead th.sorting:after,
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            font-family: "bootstrap-icons"; /* Or use Lucide icons if preferred */
            padding-left: 10px;
            opacity: 0.5;
        }
        table.dataTable thead th.sorting_asc:after {
            content: " ↑";
            opacity: 1;
            color: #2563eb; /* Blue-600 */
        }
        table.dataTable thead th.sorting_desc:after {
            content: " ↓";
            opacity: 1;
            color: #2563eb;
        }

        /* Styling the Info Text (Showing x to y of z) */
        .dataTables_info {
            @apply text-sm text-slate-500 font-medium !important;
        }

        /* Styling Pagination Container */
        .dataTables_paginate {
            @apply flex items-center gap-1 !important;
        }

        /* Styling the Pagination Buttons */
        .paginate_button {
            @apply px-3 py-1.5 rounded-lg border border-slate-200 text-sm font-semibold text-slate-600 transition-all cursor-pointer !important;
            background: white !important;
        }

        .paginate_button:hover {
            @apply bg-slate-50 text-blue-600 border-blue-200 !important;
        }

        .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-100 !important;
        }

        .paginate_button.disabled {
            @apply opacity-40 cursor-not-allowed !important;
        }

        /* Clean up the Search and Length wrappers */
        .dataTables_wrapper .flex {
            @apply items-center !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }


        .ui-sortable-helper {
            display: table !important;
            width: 100% !important;
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col">

    <!-- Navigation -->
    @include('layouts.includes.nav')

    <!-- Main Content -->
    <main class="grow">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.includes.footer')

    <input type="hidden" id="url" value="{{ url('/') }}">

    <!-- Scripts -->
    <!-- 1. jQuery (Must be first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 2. jQuery UI (Required for sortable) -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- 3. DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>

    <!-- 4. Lucide & Others -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        // Initialize global settings
        let url = $('#url').val();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>

    @stack('scripts')
</body>
</html>
