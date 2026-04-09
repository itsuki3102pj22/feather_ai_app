<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white shadow-sm min-h-screen p-6">
            <nav class="space-y-2">
                <a class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-700 font-bold rounded-lg transition-all duration-300 ease-out hover:translate-x-1 text-sm" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>ダッシュボード</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all duration-300 ease-out hover:translate-x-1 text-sm" href="{{ route('simulator.form') }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calculate</span>
                    <span>価格計算</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all duration-300 ease-out hover:translate-x-1 text-sm" href="{{ route('price_chart.index') }}">
                    <span class="material-symbols-outlined">trending_up</span>
                    <span>価格推移</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all duration-300 ease-out hover:translate-x-1 text-sm" href="{{ route('customers.index') }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>得意先管理</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-all duration-300 ease-out hover:translate-x-1 text-sm" href="{{ route('simulator.history') }}">
                    <span class="material-symbols-outlined">history</span>
                    <span>履歴</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600">group</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">得意先数</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $customerCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <span class="material-symbols-outlined text-green-600">attach_money</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">今月出荷金額</p>
                            <p class="text-2xl font-bold text-gray-900">¥{{ number_format($monthlyShipped) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <span class="material-symbols-outlined text-yellow-600">calculate</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">計算履歴</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $recentSimulations->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <span class="material-symbols-outlined text-purple-600">contract</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">進行中契約</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeContracts->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Simulations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">最近の計算履歴</h3>
                    @if($recentSimulations->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentSimulations as $simulation)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium">{{ $simulation->feather_type }}</p>
                                        <p class="text-sm text-gray-600">{{ $simulation->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                    <a href="{{ route('simulator.result', $simulation) }}" class="text-blue-600 hover:text-blue-800 text-sm">詳細</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">計算履歴がありません。</p>
                    @endif
                </div>
            </div>

            <!-- Active Contracts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">進行中の契約</h3>
                    @if($activeContracts->count() > 0)
                        <div class="space-y-3">
                            @foreach($activeContracts as $contract)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium">{{ $contract->customer->name ?? '不明' }}</p>
                                        <p class="text-sm text-gray-600">{{ $contract->feather_type }} - 残り {{ number_format($contract->contract_kg - $contract->shipped_kg) }}kg</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">進行中</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">進行中の契約がありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
