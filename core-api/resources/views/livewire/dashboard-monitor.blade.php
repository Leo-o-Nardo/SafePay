<div class="min-h-screen bg-[#0B1120] text-slate-400 font-sans pb-10">

    <nav class="border-b border-slate-800 bg-[#0B1120]/80 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-tr from-indigo-600 to-blue-500 text-white p-2 rounded-lg shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white tracking-tight">SafePay <span class="text-indigo-400">Demo</span></h1>
                        <div class="flex items-center gap-1.5">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-[10px] font-mono text-emerald-400 uppercase tracking-widest">System Online</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-sm font-semibold text-white">{{ $userName }}</span>
                        <span class="text-xs text-slate-500 font-mono">ID: {{ substr(md5($userName), 0, 8) }}</span>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center text-indigo-400 font-bold border border-slate-700 ring-2 ring-transparent hover:ring-indigo-500/50 transition-all cursor-pointer">
                        {{ substr($userName, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-[#0f172a] border-b border-slate-800 px-4 py-2 flex justify-end gap-3">
        <button wire:click="retryDlq" wire:loading.attr="disabled" class="group text-[11px] font-bold text-yellow-500 hover:text-yellow-300 border border-yellow-500/20 bg-yellow-500/5 hover:bg-yellow-500/10 px-3 py-1.5 rounded flex items-center gap-2 transition-all">
            <svg class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            REPROCESSAR DLQ
        </button>

        <button wire:confirm="Isso apagará todo o banco de dados. Confirmar?" wire:click="resetSystem" class="text-[11px] font-bold text-red-500 hover:text-red-400 border border-red-500/20 bg-red-500/5 hover:bg-red-500/10 px-3 py-1.5 rounded flex items-center gap-2 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            FACTORY RESET
        </button>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session()->has('success'))
            <div class="mb-8 rounded-lg bg-emerald-500/10 p-4 border border-emerald-500/20 flex items-center gap-3 animate-fade-in-down">
                <div class="flex-shrink-0 bg-emerald-500/20 p-1 rounded-full">
                    <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-emerald-300">Operação realizada</p>
                    <p class="text-xs text-emerald-500/70">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-5 space-y-6">

                <div class="grid grid-cols-2 gap-4">
                    <div class="group bg-slate-800/50 hover:bg-slate-800 rounded-2xl border border-slate-700/50 p-5 relative overflow-hidden transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Saldo em Conta</dt>
                        <dd class="mt-2 text-3xl font-bold text-white tracking-tight">
                            R$ {{ number_format($balance, 2, ',', '.') }}
                        </dd>
                        <div class="mt-4 flex items-center text-xs font-medium text-emerald-400">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                            Disponível
                        </div>
                    </div>

                    <div class="group bg-slate-800/50 hover:bg-slate-800 rounded-2xl border border-slate-700/50 p-5 relative overflow-hidden transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <dt class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cashback</dt>
                            <dd class="mt-2 text-2xl font-bold text-white tracking-tight">
                                R$ {{ number_format($cashback, 2, ',', '.') }}
                            </dd>
                        </div>

                        @if($cashback > 0)
                            <div class="mt-4">
                                <button wire:click="exchangeCashback" class="w-full text-xs font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 py-2 rounded shadow-lg shadow-purple-900/40 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    RESGATAR
                                </button>
                            </div>
                        @else
                            <div class="mt-4 text-xs text-slate-600">Acumule usando cartão ou Pix.</div>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-800/50 rounded-xl border border-slate-700/50 p-4 flex gap-3 shadow-lg">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-green-500 font-bold">$</span>
                        </div>
                        <input wire:model="topupAmount" type="number"
                            class="block w-full bg-slate-900/50 border border-slate-700 rounded-lg pl-8 pr-3 py-2 text-white text-sm focus:ring-1 focus:ring-green-500 focus:border-green-500 transition-colors"
                            placeholder="Adicionar saldo...">
                    </div>
                    <button wire:click="deposit" class="bg-green-600/20 hover:bg-green-600/30 text-green-400 border border-green-500/30 rounded-lg px-4 text-sm font-bold transition-colors">
                        DEPOSITAR
                    </button>
                </div>

                <div class="bg-slate-800/80 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
                    <div class="bg-slate-900/50 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <span class="bg-indigo-500/20 text-indigo-400 p-1 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </span>
                            Checkout
                        </h3>
                        <span class="text-xs bg-slate-800 text-slate-400 px-2 py-1 rounded border border-slate-700">Simulador</span>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-2 uppercase">Valor do Pagamento</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold">R$</span>
                                </div>
                                <input wire:model="paymentAmount" type="number" step="0.01"
                                    class="block w-full bg-slate-900 border border-slate-700 rounded-lg pl-10 pr-4 py-3 text-white placeholder-slate-600 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-mono text-lg"
                                    placeholder="0.00">
                            </div>
                            @error('paymentAmount') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-3 uppercase">Forma de Pagamento</label>
                            <div class="space-y-3">

                                <label class="relative flex cursor-pointer rounded-xl border p-4 transition-all duration-200
                                    {{ $paymentMethod === 'wallet' ? 'bg-indigo-600/10 border-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.15)]' : 'bg-slate-900 border-slate-700 hover:border-slate-600' }}">
                                    <input type="radio" wire:model.live="paymentMethod" value="wallet" class="sr-only">
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-5 w-5 rounded-full border flex items-center justify-center {{ $paymentMethod === 'wallet' ? 'border-indigo-500' : 'border-slate-600' }}">
                                                @if($paymentMethod === 'wallet') <div class="h-2.5 w-2.5 rounded-full bg-indigo-500"></div> @endif
                                            </div>
                                            <div>
                                                <span class="block text-sm font-bold text-white">Saldo em Conta</span>
                                                <span class="block text-xs text-slate-500">Instantâneo</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-mono {{ $paymentMethod === 'wallet' ? 'text-indigo-300' : 'text-slate-500' }}">
                                            R$ {{ number_format($balance, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-xl border p-4 transition-all duration-200
                                    {{ $paymentMethod === 'pix' ? 'bg-teal-600/10 border-teal-500 shadow-[0_0_15px_rgba(20,184,166,0.15)]' : 'bg-slate-900 border-slate-700 hover:border-slate-600' }}">
                                    <input type="radio" wire:model.live="paymentMethod" value="pix" class="sr-only">
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-5 w-5 rounded-full border flex items-center justify-center {{ $paymentMethod === 'pix' ? 'border-teal-500' : 'border-slate-600' }}">
                                                @if($paymentMethod === 'pix') <div class="h-2.5 w-2.5 rounded-full bg-teal-500"></div> @endif
                                            </div>
                                            <div>
                                                <span class="block text-sm font-bold text-white">Pix (+5% Cashback)</span>
                                                <span class="block text-xs text-slate-500">90% de aprovação (Delay simulado entre 1s e 10s)</span>
                                            </div>
                                        </div>
                                        <svg class="h-5 w-5 {{ $paymentMethod === 'pix' ? 'text-teal-500' : 'text-slate-600' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-2.29-6.556l-3.32-3.889 3.32-3.889h4.58l3.32 3.889-3.32 3.889h-4.58z"/></svg>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-xl border p-4 transition-all duration-200
                                    {{ $paymentMethod === 'credit_card' ? 'bg-indigo-600/10 border-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.15)]' : 'bg-slate-900 border-slate-700 hover:border-slate-600' }}">
                                    <input type="radio" wire:model.live="paymentMethod" value="credit_card" class="sr-only">
                                    <div class="flex w-full items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-5 w-5 rounded-full border flex items-center justify-center {{ $paymentMethod === 'credit_card' ? 'border-indigo-500' : 'border-slate-600' }}">
                                                @if($paymentMethod === 'credit_card') <div class="h-2.5 w-2.5 rounded-full bg-indigo-500"></div> @endif
                                            </div>
                                            <div>
                                                <span class="block text-sm font-bold text-white">Cartão de Crédito</span>
                                                <span class="block text-xs text-slate-500">50% de aprovação (Delay simulado entre 10s e 20s)</span>
                                            </div>
                                        </div>
                                        <svg class="h-5 w-5 {{ $paymentMethod === 'credit_card' ? 'text-indigo-500' : 'text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4 flex items-center justify-between bg-red-500/5 border border-red-500/20 p-3 rounded-lg group hover:bg-red-500/10 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-red-400 uppercase tracking-wider">Simular Falha Fatal</span>
                                    <span class="text-[10px] text-red-500/60">Força envio para Dead Letter Queue</span>
                                </div>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="forceError" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </div>

                        <button wire:click="makePayment"
                            class="w-full py-4 rounded-xl font-bold text-white shadow-lg transition-all duration-200 transform active:scale-[0.98]
                            {{ ($paymentMethod === 'wallet' && $balance < ($paymentAmount ?: 0))
                                ? 'bg-slate-700 text-slate-500 cursor-not-allowed border border-slate-600'
                                : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-indigo-500/25 border border-indigo-400/20' }}">

                            <span wire:loading.remove wire:target="makePayment" class="flex items-center justify-center gap-2">
                                PAGAR AGORA
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <span wire:loading wire:target="makePayment" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processando...
                            </span>
                        </button>
                    </div>
                </div>


            </div>


            <div class="lg:col-span-7 space-y-6">

                <div>
                    <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider pl-1">Métricas em Tempo Real</h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl text-center">
                            <span class="block text-[10px] uppercase text-slate-500 font-bold">Total</span>
                            <span class="block text-2xl font-bold text-white mt-1">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                        <div class="bg-slate-800 border border-slate-700 border-b-2 border-b-emerald-500 p-4 rounded-xl text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-emerald-500/5"></div>
                            <span class="block text-[10px] uppercase text-emerald-400 font-bold">Aprovados</span>
                            <span class="block text-2xl font-bold text-white mt-1">{{ $stats['paid'] ?? 0 }}</span>
                        </div>
                        <div class="bg-slate-800 border border-slate-700 border-b-2 border-b-yellow-500 p-4 rounded-xl text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-yellow-500/5"></div>
                            <span class="block text-[10px] uppercase text-yellow-400 font-bold">Pendentes</span>
                            <span class="block text-2xl font-bold text-white mt-1">{{ $stats['pending'] ?? 0 }}</span>
                        </div>
                        <div class="bg-slate-800 border border-slate-700 border-b-2 border-b-red-500 p-4 rounded-xl text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-red-500/5"></div>
                            <span class="block text-[10px] uppercase text-red-400 font-bold">RECUSADOS</span>
                            <span class="block text-2xl font-bold text-white mt-1">{{ $stats['failed'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-16 h-16 text-indigo-400" viewBox="0 0 24 24" fill="currentColor"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Fila de Processamento</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-mono font-bold text-white">{{ $rabbitStats['ready'] ?? 0 }}</p>
                            <span class="text-xs text-slate-500">msgs</span>
                        </div>
                        @if(($rabbitStats['ready'] ?? 0) > 0)
                            <div class="mt-2 text-[10px] text-indigo-300 flex items-center gap-1">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                                </span>
                                Processando...
                            </div>
                        @endif
                    </div>

                    <div class="bg-slate-800 border border-slate-700 p-5 rounded-xl relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-16 h-16 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-1">Dead Letter Queue</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-mono font-bold text-white">{{ $dlqStats['ready'] ?? 0 }}</p>
                            <span class="text-xs text-slate-500">erros</span>
                        </div>
                         @if(($dlqStats['ready'] ?? 0) > 0)
                             <div class="mt-2 text-[10px] text-red-400">Ação necessária</div>
                         @endif
                    </div>
                </div>

                <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
                    <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-200">Ledger de Transações</h3>
                        <span class="text-[10px] bg-slate-900 text-slate-500 px-2 py-1 rounded border border-slate-700">Live Feed</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-700/50">
                            <thead class="bg-slate-900/30">
                                <tr>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">ID / Hora</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Método</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/50">
                                @forelse($payments as $payment)
                                <tr class="hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-200">{{ substr($payment->id, 0, 8) }}...</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $payment->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-white">R$ {{ number_format($payment->amount, 2, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->method == 'wallet')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                Wallet
                                            </span>
                                        @elseif($payment->method == 'pix')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">
                                                Pix
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                Crédito
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->status == 'PAID')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Aprovado
                                            </span>
                                        @elseif($payment->status == 'FAILED')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                                Recusado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                                Fila
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 italic">
                                        Nenhuma transação registrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
