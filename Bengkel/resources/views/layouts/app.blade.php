<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>JEKI SPEED | Workshop Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F97316', primaryHover: '#EA580C', secondary: '#3B82F6',
                        tertiary: '#22C55E', surface: '#FFFFFF', success: '#22C55E',
                        warning: '#EAB308', error: '#EF4444', background: '#F9FAFB'
                    },
                    fontFamily: { headline: ['Fredoka', 'sans-serif'], body: ['Poppins', 'sans-serif'], mono: ['"Roboto Mono"', 'monospace'] },
                    boxShadow: { 'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.06)', 'md': '0 4px 6px -1px rgba(0, 0, 0, 0.10)', 'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.10)', 'focus': '0 0 0 3px rgba(249, 115, 22, 0.3)' },
                    borderRadius: { 'sm': '6px', 'md': '12px', 'lg': '16px', 'pill': '9999px', 'circle': '50%' }
                }
            }
        }
    </script>
    <style>
        .lf-card { background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.10); transition: box-shadow 0.2s ease; }
        .lf-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.10); }
        .lf-btn-primary { background-color: #F97316; color: #FFFFFF; border-radius: 12px; transition: all 0.2s; }
        .lf-btn-primary:hover { background-color: #EA580C; }
        .lf-btn-secondary { background-color: #FFFFFF; color: #F97316; border: 1px solid #F97316; border-radius: 12px; transition: all 0.2s; }
        .lf-btn-secondary:hover { background-color: #FFF7ED; }
        .lf-input { border: 1px solid #D1D5DB; border-radius: 6px; padding: 12px 16px; font-family: 'Poppins', sans-serif; transition: all 0.2s; }
        .lf-input:focus { outline: none; border-color: #F97316; box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3); }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-background text-gray-800 font-body flex overflow-x-hidden">

    <!-- SIDEBAR -->
    <aside class="hidden xl:flex w-[280px] h-screen fixed left-0 top-0 bg-surface border-r border-gray-200 flex-col py-8 z-50">
        <div class="px-6 mb-10 text-center">
            <h1 class="text-4xl font-bold text-primary font-headline tracking-wide">JEKI SPEED</h1>
            <p class="text-xs font-medium tracking-widest text-secondary mt-1">WORKSHOP SYSTEM</p>
        </div>
        <nav class="flex-1 space-y-2 px-4">
            <a href="/" class="flex items-center gap-4 px-4 py-3 rounded-md font-medium transition-all {{ Request::is('/') ? 'bg-[#FFF7ED] text-primary border border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                <span class="material-symbols-outlined">dashboard</span><span>Dashboard</span>
            </a>
            <a href="/billing" class="flex items-center gap-4 px-4 py-3 rounded-md font-medium transition-all {{ Request::is('billing') ? 'bg-[#FFF7ED] text-primary border border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                <span class="material-symbols-outlined">receipt_long</span><span>Billing & Notes</span>
            </a>
            <a href="/warehouse" class="flex items-center gap-4 px-4 py-3 rounded-md font-medium transition-all {{ Request::is('warehouse') ? 'bg-[#FFF7ED] text-primary border border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }}">
                <span class="material-symbols-outlined">inventory_2</span><span>Warehouse</span>
            </a>
            <div class="my-4 border-t border-gray-200/60"></div>
            <a href="/kios" target="_blank" class="flex items-center gap-4 px-4 py-3 rounded-md font-medium text-gray-500 hover:text-primary hover:bg-gray-50 transition-all group">
                <span class="material-symbols-outlined">devices</span><span>Buka Kios Depan</span>
                <span class="material-symbols-outlined ml-auto text-[16px] text-gray-400 group-hover:text-primary">open_in_new</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 xl:ml-[280px] min-h-screen flex flex-col">
        <!-- TOP NAVIGATION -->
        <header class="flex justify-between items-center px-8 h-20 sticky top-0 z-40 bg-surface/90 backdrop-blur-md border-b border-gray-200">
            <div class="xl:hidden font-bold font-headline text-primary text-2xl">JEKI SPEED</div>
            <div class="hidden md:flex items-center gap-4 relative w-96">
                <span class="material-symbols-outlined absolute left-4 text-gray-400">search</span>
                <input class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary" placeholder="Pencarian cepat..." type="text"/>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 px-4 py-1.5 bg-[#BBF7D0] text-[#15803D] rounded-pill text-xs font-semibold">
                    <span class="material-symbols-outlined text-[16px]">wifi</span><span>Terhubung</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-circle bg-primary flex items-center justify-center text-white font-bold font-headline text-lg">ZA</div>
                </div>
            </div>
        </header>

        <div class="p-8 max-w-7xl w-full mx-auto flex-1">
            @yield('content')
        </div>
    </main>

    <div id="overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden transition-opacity"></div>
    @stack('scripts')
</body>
</html>
