<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engineering Team Insights Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Engineering Team Insights</h1>
                            <p class="text-sm text-gray-500">Automated performance tracking and team analytics powered by GitHub</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <button onclick="syncData()" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Sync Data</span>
                        </button>
                        <button onclick="openSettings()" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">Settings</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Time Period Tabs -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button onclick="changePeriod('weekly')" class="period-tab active px-6 py-2 rounded-md text-sm font-medium transition-all duration-200" data-period="weekly">
                    Weekly
                </button>
                <button onclick="changePeriod('quarterly')" class="period-tab px-6 py-2 rounded-md text-sm font-medium transition-all duration-200" data-period="quarterly">
                    Quarterly
                </button>
                <button onclick="changePeriod('yearly')" class="period-tab px-6 py-2 rounded-md text-sm font-medium transition-all duration-200" data-period="yearly">
                    Yearly
                </button>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($metrics as $metric)
            <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <i data-lucide="{{ $metric['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-600">{{ $metric['title'] }}</h3>
                    </div>
                    <div class="text-right">
                        <i data-lucide="{{ $metric['change_type'] === 'positive' ? 'trending-up' : 'trending-down' }}" 
                           class="w-4 h-4 {{ $metric['change_type'] === 'positive' ? 'text-green-500' : 'text-red-500' }}"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="text-2xl font-bold text-gray-900">{{ $metric['value'] }}</div>
                    <div class="flex items-center space-x-1">
                        <span class="text-sm font-medium {{ $metric['change_type'] === 'positive' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $metric['change'] }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $metric['description'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Team Rankings -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Team Rankings - {{ ucfirst($activePeriod) }}</h2>
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button onclick="changeRankingPeriod('weekly')" class="ranking-tab {{ $activeRankingPeriod === 'weekly' ? 'active' : '' }} px-4 py-1 rounded-md text-sm font-medium transition-all duration-200" data-period="weekly">
                            Weekly
                        </button>
                        <button onclick="changeRankingPeriod('quarterly')" class="ranking-tab {{ $activeRankingPeriod === 'quarterly' ? 'active' : '' }} px-4 py-1 rounded-md text-sm font-medium transition-all duration-200" data-period="quarterly">
                            Quarterly
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($teamMembers as $member)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="flex items-center space-x-3">
                                @if($member['rank'] === 1)
                                    <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                                @elseif($member['rank'] === 2)
                                    <i data-lucide="medal" class="w-5 h-5 text-gray-400"></i>
                                @elseif($member['rank'] === 3)
                                    <i data-lucide="award" class="w-5 h-5 text-amber-600"></i>
                                @else
                                    <div class="w-5 h-5"></div>
                                @endif
                                <img src="{{ $member['avatar'] }}" alt="{{ $member['name'] }}" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-medium text-gray-900">{{ $member['name'] }}</h3>
                                    @if(isset($member['badge']))
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ $member['badge'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $member['commits'] }} commits • {{ $member['reviews'] }} reviews
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-32">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-900">{{ $member['score'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $member['score'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Awards & Recognition -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-2 mb-6">
                    <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                    <h2 class="text-lg font-semibold text-gray-900">Awards & Recognition</h2>
                </div>
                
                @if($topContributor)
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="star" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $topContributor['name'] }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $topContributor['badge'] }}</p>
                    <p class="text-sm text-gray-600 mb-4">{{ $topContributor['commits'] }} commits, {{ $topContributor['reviews'] }} reviews</p>
                    <button onclick="sendCongratulations('{{ $topContributor['id'] }}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                        Send Congratulations
                    </button>
                </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="flame" class="w-4 h-4 text-orange-500"></i>
                            <span class="text-sm text-gray-600">Most commits this week</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i data-lucide="code" class="w-4 h-4 text-green-500"></i>
                            <span class="text-sm text-gray-600">Highest review quality</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i data-lucide="trending-up" class="w-4 h-4 text-blue-500"></i>
                            <span class="text-sm text-gray-600">Best improvement rate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-6 mb-8">
            <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-6" style = "margin-right: 30px;">
                <h2 class="text-lg font-semibold text-gray-900">Work Distribution</h2>
                <div class="lg:col-span-2 bg-white flex flex-col space-y-1.5">
                    <div class="recharts-responsive-container lg:col-span-2 bg-white">
                        <div class="recharts-wrapper">
                            <svg cx="50%" cy="50%" class="recharts-surface" width="620" height="300" viewBox="0 0 620 300" style="width: 100%; height: 100%;">
                                <title></title>
                                <desc></desc>
                                <defs>
                                    <clipPath id="recharts9-clip">
                                        <rect x="5" y="5" height="290" width="610"></rect>
                                    </clipPath>
                                </defs>
                                <g class="recharts-layer recharts-pie" tabindex="0">
                                    <g class="recharts-layer">
                                        <g class="recharts-layer recharts-pie-sector" tabindex="-1">
                                            <path cx="310" cy="150" name="Frontend" stroke="#fff" fill="#3B82F6" color="#3B82F6" tabindex="-1" class="recharts-sector" d="M 390,150
                                            A 80,80,0,
                                            0,0,
                                            262.9771798166022,85.2786404500042
                                            L 310,150 Z" role="img"></path>
                                        </g>
                                        <g class="recharts-layer recharts-pie-sector" tabindex="-1">
                                            <path cx="310" cy="150" name="Backend" stroke="#fff" fill="#8B5CF6" color="#8B5CF6" tabindex="-1" class="recharts-sector" d="M 262.9771798166022,85.2786404500042
                                            A 80,80,0,
                                            0,0,
                                            262.97717981660213,214.72135954999578
                                            L 310,150 Z" role="img"></path>
                                        </g>
                                        <g class="recharts-layer recharts-pie-sector" tabindex="-1">
                                            <path cx="310" cy="150" name="DevOps" stroke="#fff" fill="#10B981" color="#10B981" tabindex="-1" class="recharts-sector" d="M 262.97717981660213,214.72135954999578
                                            A 80,80,0,
                                            0,0,
                                            357.0228201833978,214.7213595499958
                                            L 310,150 Z" role="img"></path>
                                        </g>
                                        <g class="recharts-layer recharts-pie-sector" tabindex="-1">
                                            <path cx="310" cy="150" name="Testing" stroke="#fff" fill="#F59E0B" color="#F59E0B" tabindex="-1" class="recharts-sector" d="M 357.0228201833978,214.7213595499958
                                            A 80,80,0,
                                            0,0,
                                            390,150.00000000000003
                                            L 310,150 Z" role="img"></path>
                                        </g>
                                    </g>
                                    <g class="recharts-layer recharts-pie-labels">
                                        <g class="recharts-layer">
                                            <path cx="310" cy="150" fill="none" stroke="#3B82F6" name="Frontend" color="#3B82F6" class="recharts-curve recharts-pie-label-line" d="M346.319,78.719L355.399,60.899"></path>
                                            <text cx="310" cy="150" stroke="none" name="Frontend" color="#3B82F6" alignment-baseline="middle" x="355.3990499739547" y="60.89934758116323" class="recharts-text recharts-pie-label-text" text-anchor="start" fill="#3B82F6">
                                            <tspan x="355.3990499739547" dy="0em">Frontend: 35%</tspan>
                                            </text>
                                        </g>
                                        <g class="recharts-layer">
                                            <path cx="310" cy="150" fill="none" stroke="#8B5CF6" name="Backend" color="#8B5CF6" class="recharts-curve recharts-pie-label-line" d="M230,150L210,150"></path>
                                            <text cx="310" cy="150" stroke="none" name="Backend" color="#8B5CF6" alignment-baseline="middle" x="210" y="150" class="recharts-text recharts-pie-label-text" text-anchor="end" fill="#8B5CF6">
                                            <tspan x="210" dy="0em">Backend: 30%</tspan>
                                            </text>
                                        </g>
                                        <g class="recharts-layer">
                                            <path cx="310" cy="150" fill="none" stroke="#10B981" name="DevOps" color="#10B981" class="recharts-curve recharts-pie-label-line" d="M310,230L310,250"></path>
                                            <text cx="310" cy="150" stroke="none" name="DevOps" color="#10B981" alignment-baseline="middle" x="310" y="250" class="recharts-text recharts-pie-label-text" text-anchor="middle" fill="#10B981">
                                            <tspan x="310" dy="0em">DevOps: 20%</tspan>
                                            </text>
                                        </g>
                                        <g class="recharts-layer">
                                            <path cx="310" cy="150" fill="none" stroke="#F59E0B" name="Testing" color="#F59E0B" class="recharts-curve recharts-pie-label-line" d="M381.281,186.319L399.101,195.399"></path>
                                            <text cx="310" cy="150" stroke="none" name="Testing" color="#F59E0B" alignment-baseline="middle" x="399.1006524188368" y="195.3990499739547" class="recharts-text recharts-pie-label-text" text-anchor="start" fill="#F59E0B">
                                            <tspan x="399.1006524188368" dy="0em">Testing: 15%</tspan>
                                            </text>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-3 bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Bug Resolution Trends</h2>    
                <div data-lov-id="src/components/PerformanceCharts.tsx:164:8" data-lov-name="CardContent" data-component-path="src/components/PerformanceCharts.tsx" data-component-line="164" data-component-file="PerformanceCharts.tsx" data-component-name="CardContent" data-component-content="%7B%7D" class="p-6 pt-0">
                    <div class="recharts-responsive-container" style="width: 100%; height: 300px; min-width: 0px;">
                        <div class="recharts-wrapper" style="position: relative; cursor: default; width: 100%; height: 100%; max-height: 300px; max-width: 501px;">
                            <svg class="recharts-surface" width="501" height="300" viewBox="0 0 501 300" style="width: 100%; height: 100%;">
                            <title></title>
                            <desc></desc>
                            <defs>
                                <clipPath id="recharts11-clip">
                                    <rect x="65" y="5" height="260" width="431"></rect>
                                </clipPath>
                            </defs>
                            <g class="recharts-cartesian-grid">
                                <g class="recharts-cartesian-grid-horizontal">
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="265" x2="496" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="200" x2="496" y2="200"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="135" x2="496" y2="135"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="70" x2="496" y2="70"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="5" x2="496" y2="5"></line>
                                </g>
                                <g class="recharts-cartesian-grid-vertical">
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="65" y1="5" x2="65" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="136.83333333333331" y1="5" x2="136.83333333333331" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="208.66666666666666" y1="5" x2="208.66666666666666" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="280.5" y1="5" x2="280.5" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="352.3333333333333" y1="5" x2="352.3333333333333" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="424.16666666666663" y1="5" x2="424.16666666666663" y2="265"></line>
                                    <line stroke-dasharray="3 3" opacity="0.3" stroke="#ccc" fill="none" x="65" y="5" width="431" height="260" x1="496" y1="5" x2="496" y2="265"></line>
                                </g>
                            </g>
                            <g class="recharts-layer recharts-cartesian-axis recharts-xAxis xAxis">
                                <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-line" stroke="#666" fill="none" x1="65" y1="265" x2="496" y2="265"></line>
                                <g class="recharts-cartesian-axis-ticks">
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="65" y1="271" x2="65" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="65" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="65" dy="0.71em">Mon</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="136.83333333333331" y1="271" x2="136.83333333333331" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="136.83333333333331" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="136.83333333333331" dy="0.71em">Tue</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="208.66666666666666" y1="271" x2="208.66666666666666" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="208.66666666666666" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="208.66666666666666" dy="0.71em">Wed</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="280.5" y1="271" x2="280.5" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="280.5" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="280.5" dy="0.71em">Thu</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="352.3333333333333" y1="271" x2="352.3333333333333" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="352.3333333333333" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="352.3333333333333" dy="0.71em">Fri</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="424.16666666666663" y1="271" x2="424.16666666666663" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="424.16666666666663" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="424.16666666666663" dy="0.71em">Sat</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="bottom" width="431" height="30" x="65" y="265" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="496" y1="271" x2="496" y2="265"></line>
                                        <text orientation="bottom" width="431" height="30" stroke="none" x="487.03515625" y="273" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="middle" fill="#666">
                                        <tspan x="487.03515625" dy="0.71em">Sun</tspan>
                                        </text>
                                    </g>
                                </g>
                            </g>
                            <g class="recharts-layer recharts-cartesian-axis recharts-yAxis yAxis">
                                <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-line" stroke="#666" fill="none" x1="65" y1="5" x2="65" y2="265"></line>
                                <g class="recharts-cartesian-axis-ticks">
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="59" y1="265" x2="65" y2="265"></line>
                                        <text orientation="left" width="60" height="260" stroke="none" x="57" y="265" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="end" fill="#666">
                                        <tspan x="57" dy="0.355em">0</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="59" y1="200" x2="65" y2="200"></line>
                                        <text orientation="left" width="60" height="260" stroke="none" x="57" y="200" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="end" fill="#666">
                                        <tspan x="57" dy="0.355em">0.75</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="59" y1="135" x2="65" y2="135"></line>
                                        <text orientation="left" width="60" height="260" stroke="none" x="57" y="135" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="end" fill="#666">
                                        <tspan x="57" dy="0.355em">1.5</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="59" y1="70" x2="65" y2="70"></line>
                                        <text orientation="left" width="60" height="260" stroke="none" x="57" y="70" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="end" fill="#666">
                                        <tspan x="57" dy="0.355em">2.25</tspan>
                                        </text>
                                    </g>
                                    <g class="recharts-layer recharts-cartesian-axis-tick">
                                        <line orientation="left" width="60" height="260" x="5" y="5" class="recharts-cartesian-axis-tick-line" stroke="#666" fill="none" x1="59" y1="5" x2="65" y2="5"></line>
                                        <text orientation="left" width="60" height="260" stroke="none" x="57" y="12" class="recharts-text recharts-cartesian-axis-tick-value" text-anchor="end" fill="#666">
                                        <tspan x="57" dy="0.355em">3</tspan>
                                        </text>
                                    </g>
                                </g>
                            </g>
                            <g class="recharts-layer recharts-line">
                                <path stroke="#EF4444" stroke-width="3" fill="none" width="431" height="260" class="recharts-curve recharts-line-curve" stroke-dasharray="924.3368530273438px 0px" d="M65,91.667C88.944,135,112.889,178.333,136.833,178.333C160.778,178.333,184.722,5,208.667,5C232.611,5,256.556,178.333,280.5,178.333C304.444,178.333,328.389,91.667,352.333,91.667C376.278,91.667,400.222,265,424.167,265C448.111,265,472.056,221.667,496,178.333"></path>
                                <g class="recharts-layer"></g>
                                <g class="recharts-layer recharts-line-dots">
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="65" cy="91.66666666666667" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="136.83333333333331" cy="178.33333333333334" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="208.66666666666666" cy="5" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="280.5" cy="178.33333333333334" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="352.3333333333333" cy="91.66666666666667" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="424.16666666666663" cy="265" class="recharts-dot recharts-line-dot"></circle>
                                    <circle r="4" stroke="#EF4444" stroke-width="2" fill="#EF4444" width="431" height="260" cx="496" cy="178.33333333333334" class="recharts-dot recharts-line-dot"></circle>
                                </g>
                            </g>
                            </svg>
                            <div tabindex="-1" class="recharts-tooltip-wrapper recharts-tooltip-wrapper-right recharts-tooltip-wrapper-bottom" style="visibility: hidden; pointer-events: none; position: absolute; top: 0px; left: 0px; transform: translate(65px, 10px);">
                            <div class="recharts-default-tooltip" style="margin: 0px; padding: 10px; background-color: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); white-space: nowrap;">
                                <p class="recharts-tooltip-label" style="margin: 0px;"></p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>