<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>JEKI SPEED | Violet Issue</title>
    
    <!-- Violet Issue Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5E6AD2', // Linear Violet
                        primaryHover: '#4B55B0', 
                        bgBase: '#101014',
                        bgPanel: '#1B1B25',
                        bgHover: '#1F1F2E',
                        borderPanel: '#2C2C3A',
                        textMain: '#E4E4E5',
                        textSec: '#8A8F98',
                        statusGold: '#F0C000',
                        statusEmerald: '#3DD68C',
                        statusRed: '#EB5757',
                    },
                    fontFamily: { 
                        body: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    },
                }
            }
        }
    </script>
    <style>
        body { background-color: #101014; color: #E4E4E5; }
        
        /* Violet Issue Card Component */
        .lf-card { 
            background: #1B1B25; 
            border-radius: 8px; 
            border: 1px solid #2C2C3A; 
            box-shadow: none; 
            overflow: hidden;
        }
        .lf-card-body { padding: 12px 16px; }

        /* Violet Issue Buttons */
        .lf-btn-primary { 
            background-color: #5E6AD2; 
            color: #FFFFFF; 
            border-radius: 6px; 
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s; 
            box-shadow: none;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .lf-btn-primary:hover { background-color: #4B55B0; }
        
        .lf-btn-secondary { 
            background: #1B1B25; 
            color: #E4E4E5; 
            border-radius: 6px; 
            border: 1px solid #2C2C3A; 
            font-weight: 500;
            font-size: 13px;
            height: 32px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .lf-btn-secondary:hover { background: #1F1F2E; border-color: #5E6AD2; }

        /* Violet Issue Inputs */
        .lf-input { 
            background: #1F1F2E !important; 
            border: 1px solid #38384A !important; 
            color: #E4E4E5 !important; 
            border-radius: 9999px !important; 
            height: 38px;
            padding: 0 16px; 
            font-family: 'Inter', sans-serif; 
            font-size: 13px;
            transition: all 0.2s; 
            box-shadow: none !important;
        }
        .lf-input::placeholder { color: #8A8F98 !important; }
        .lf-input:focus { outline: none; border-color: #5E6AD2 !important; box-shadow: 0 0 0 2px rgba(94,106,210,0.25) !important; }

        /* Status Checkboxes / Indicators */
        .status-circle {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1.5px solid #8A8F98;
            display: inline-block;
        }
        .status-circle.in-progress {
            border-color: #F0C000;
            background: linear-gradient(to right, #F0C000 50%, transparent 50%);
        }
        .status-circle.done {
            border-color: #3DD68C;
            background: #3DD68C;
        }

        /* Priority Chips */
        .priority-chip {
            height: 20px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .priority-chip.urgent { color: #EB5757; background: rgba(235, 87, 87, 0.15); }
        .priority-chip.medium { color: #F0C000; background: rgba(240, 192, 0, 0.15); }
        .priority-chip.success { color: #3DD68C; background: rgba(61, 214, 140, 0.15); }

        /* Scrollbars */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #2C2C3A; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #5E6AD2; }

        /* Clean overrides */
        .bg-white { background-color: transparent !important; }
        .curved-sidebar { border-top-right-radius: 0; border-bottom-right-radius: 0; border-right: 1px solid #2C2C3A; }
    </style>
</head>
<body class="font-body flex h-screen overflow-hidden bg-bgBase text-textMain text-[13px]">

    <!-- SIDEBAR -->
    <aside class="w-[72px] bg-bgPanel flex flex-col items-center py-6 z-50 shrink-0 curved-sidebar relative">
        <!-- User Profile -->
        <div class="mb-8 relative">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-borderPanel shadow-lg cursor-pointer hover:border-primary transition-colors relative">
                <img src="https://ui-avatars.com/api/?name=Z+A&background=5E6AD2&color=fff&font-size=0.4" alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 flex flex-col gap-4 w-full px-2 items-center">
            <a href="/" class="relative flex items-center justify-center w-10 h-10 rounded-[6px] transition-all group {{ Request::is('/') ? 'text-primary bg-bgHover' : 'text-textSec hover:text-textMain hover:bg-bgHover' }}">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                @if(Request::is('/')) <div class="absolute -left-2 w-[3px] h-4 bg-primary rounded-r-full"></div> @endif
                <div class="absolute left-14 bg-bgPanel border border-borderPanel text-textMain text-[11px] font-semibold px-2.5 py-1.5 rounded-md shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all whitespace-nowrap z-[100] translate-x-1 group-hover:translate-x-0">
                    Dashboard
                </div>
            </a>
            <a href="/warehouse" class="relative flex items-center justify-center w-10 h-10 rounded-[6px] transition-all group {{ Request::is('warehouse') ? 'text-primary bg-bgHover' : 'text-textSec hover:text-textMain hover:bg-bgHover' }}">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                @if(Request::is('warehouse')) <div class="absolute -left-2 w-[3px] h-4 bg-primary rounded-r-full"></div> @endif
                <div class="absolute left-14 bg-bgPanel border border-borderPanel text-textMain text-[11px] font-semibold px-2.5 py-1.5 rounded-md shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all whitespace-nowrap z-[100] translate-x-1 group-hover:translate-x-0">
                    Gudang Part
                </div>
            </a>
            <a href="/billing" class="relative flex items-center justify-center w-10 h-10 rounded-[6px] transition-all group {{ Request::is('billing') ? 'text-primary bg-bgHover' : 'text-textSec hover:text-textMain hover:bg-bgHover' }}">
                <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                @if(Request::is('billing')) <div class="absolute -left-2 w-[3px] h-4 bg-primary rounded-r-full"></div> @endif
                <div class="absolute left-14 bg-bgPanel border border-borderPanel text-textMain text-[11px] font-semibold px-2.5 py-1.5 rounded-md shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all whitespace-nowrap z-[100] translate-x-1 group-hover:translate-x-0">
                    Kasir & Tagihan
                </div>
            </a>
            
            <div class="mt-auto"></div>
            
            <a href="/kios" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-[6px] transition-all text-textSec hover:text-textMain hover:bg-bgHover group relative">
                <span class="material-symbols-outlined text-[20px]">devices</span>
                <div class="absolute left-14 bg-bgPanel border border-borderPanel text-textMain text-[11px] font-semibold px-2.5 py-1.5 rounded-md shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all whitespace-nowrap z-[100] translate-x-1 group-hover:translate-x-0">
                    Buka Kios Antrean
                </div>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative bg-bgBase p-6">
        @yield('content')
    </main>

    <div id="overlay" class="fixed inset-0 bg-[#101014]/80 backdrop-blur-sm z-50 hidden transition-opacity"></div>
    @stack('scripts')
</body>
</html>
