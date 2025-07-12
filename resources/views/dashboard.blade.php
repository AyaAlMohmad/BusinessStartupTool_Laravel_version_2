{{-- <x-app-layout> --}}
    @extends('layouts.app')
    @section('content')
        <head>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        </head>
        <style>
            /* أنماط CSS السابقة تبقى كما هي */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            /* ... باقي الأنماط تبقى كما هي ... */
        </style>
        <div class="dashboard">
            <div class="summary">
                <div class="summary-item">
                    <h3><i class="fas fa-dollar-sign icon"></i>Total Startup Costs</h3>
                    <p>${{ number_format($safeSum($startupCosts, 'amount'), 0) }}</p>
                    <span>Across all users</span>
                </div>
                <div class="summary-item">
                    <h3><i class="fas fa-chart-line icon"></i>Average Startup Cost</h3>
                    <p>${{ $startupCosts->count() > 0 ? number_format($safeSum($startupCosts, 'amount') / $startupCosts->count(), 0) : 0 }}</p>
                    <span>Per business</span>
                </div>
                <div class="summary-item">
                    <h3><i class="fas fa-hand-holding-usd icon"></i>Total Funding</h3>
                    <p>${{ number_format($securedFunding, 0) }}</p>
                    <span>All sources</span>
                </div>
                <div class="summary-item">
                    <h3><i class="fas fa-calendar-alt icon"></i>Avg. Breakeven</h3>
                    <p>{{ $averageBreakeven }} months</p>
                    <span>Expected timeline</span>
                </div>
            </div>
            <div class="details">
                <div class="funding-distribution">
                    <h3><i class="fas fa-piggy-bank icon"></i>Funding Distribution</h3>
                    <table>
                        <tr>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>%</th>
                        </tr>
                        @forelse ($fundingSources as $funding)
                        <tr>
                            <td>{{ $funding->source ?? 'N/A' }}</td>
                            <td>${{ number_format($funding->amount, 0) }}</td>
                            <td>{{ $safePercentage($funding->amount, $securedFunding) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">No funding sources available</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
                <div class="cost-categories">
                    <h3><i class="fas fa-coins icon"></i>Cost Categories</h3>
                    <table>
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>%</th>
                        </tr>
                        @forelse ($startupCosts as $cost)
                        <tr>
                            <td>{{ $cost->category ?? 'N/A' }}</td>
                            <td>${{ number_format($cost->amount, 0) }}</td>
                            <td>{{ $safePercentage($cost->amount, $safeSum($startupCosts, 'amount')) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">No cost categories available</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
            <div class="funding-status">
                <h3><i class="fas fa-chart-pie icon"></i>Funding Status Overview</h3>
                <div class="status-item">
                    <h4><i class="fas fa-tasks icon"></i>Planned</h4>
                    <p>${{ number_format($plannedFunding, 0) }}</p>
                    <span class="oval">{{ $plannedFundingPercentage }}%</span>
                </div>
                <div class="status-item">
                    <h4><i class="fas fa-check-circle icon"></i>Secured</h4>
                    <p>${{ number_format($securedFunding, 0) }}</p>
                    <span class="oval-green">{{ $securedFundingPercentage }}%</span>
                </div>
                <div class="status-item">
                    <h4><i class="fas fa-hourglass-half icon"></i>Pending</h4>
                    <p>${{ number_format($pendingFunding, 0) }}</p>
                    <span class="oval-orange">{{ $pendingFundingPercentage }}%</span>
                </div>
            </div>
    
            <!-- قسم إحصائيات الصفحات -->
            <div class="mt-8">
                <h3><i class="fas fa-file-alt icon"></i>Page Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-gray-500">Business Ideas</div>
                        <div class="text-2xl font-bold">{{ $businessIdeas }}</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-gray-500">Sales Strategies</div>
                        <div class="text-2xl font-bold">{{ $salesStrategiesCount }}</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-gray-500">Marketing News</div>
                        <div class="text-2xl font-bold">{{ $marketingNews }}</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-gray-500">Market Research</div>
                        <div class="text-2xl font-bold">{{ $marketResearches }}</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-gray-500">Start Simple</div>
                        <div class="text-2xl font-bold">{{ $startSimples }}</div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </x-app-layout> --}}
    @endsection