<div class="space-y-6 sm:space-y-8" id="statisticsTab">
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-7">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                Overview
            </h2>
            <span class="text-xs text-gray-400">Last 30 days</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="group p-4 sm:p-5 rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 hover:border-purple-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 text-purple-600 flex items-center justify-center shadow-xs">
                        👁️
                    </div>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Total Clicks</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ $stats['total_clicks'] ?? 0 }}
                </p>
            </div>
            <div class="group p-4 sm:p-5 rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 hover:border-blue-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 flex items-center justify-center shadow-xs">
                        👤
                    </div>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Unique Clicks</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    {{ $stats['unique_clicks'] ?? 0 }}
                </p>
            </div>
            <div class="group p-4 sm:p-5 rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 hover:border-emerald-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                        🌍
                    </div>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Top Country</p>
                <p class="text-lg font-semibold text-gray-900 truncate">
                    {{ $stats['top_country'] ?? 'Unknown' }}
                </p>
            </div>
            <div class="group p-4 sm:p-5 rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 hover:border-orange-100 hover:shadow-sm transition-all duration-200">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 text-orange-600 flex items-center justify-center shadow-xs">
                        🏙️
                    </div>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Top City</p>
                <p class="text-lg font-semibold text-gray-900 truncate">
                    {{ $stats['top_city'] ?? 'Unknown' }}
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-7">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                Countries & Cities
            </h2>
            <span class="text-xs text-gray-400">{{ count($stats['locations'] ?? []) }} locations</span>
        </div>

        <div class="overflow-x-auto -mx-5 sm:-mx-7 px-5 sm:px-7">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-100 text-left">
                        <th class="py-3.5 font-medium pl-3">Country</th>
                        <th class="py-3.5 font-medium">City</th>
                        <th class="py-3.5 font-medium text-right pr-3">Clicks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stats['locations'] ?? [] as $row)
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                            <td class="py-3.5 pl-3 font-medium text-gray-900">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">🌐</span>
                                    {{ $row->country ?? 'Unknown' }}
                                </div>
                            </td>
                            <td class="py-3.5 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400">📍</span>
                                    {{ $row->city ?? '—' }}
                                </div>
                            </td>
                            <td class="py-3.5 text-right pr-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-semibold text-gray-900">
                                    {{ $row->total }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-10 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                        🌍
                                    </div>
                                    <p class="text-sm font-medium">No location data available</p>
                                    <p class="text-xs mt-1">Location data will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-7">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">
                Devices
            </h2>

            <div class="space-y-2.5">
                @forelse($stats['devices'] ?? [] as $row)
                    <div class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gradient-to-r from-white to-gray-50/50 hover:border-gray-200 hover:shadow-xs transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                                @if(str_contains(strtolower($row->device ?? ''), 'mobile'))
                                    📱
                                @elseif(str_contains(strtolower($row->device ?? ''), 'tablet'))
                                    📟
                                @else
                                    💻
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $row->device ?? 'Unknown' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-900">
                                {{ $row->total }}
                            </span>
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                            📱
                        </div>
                        <p class="text-sm text-gray-400">No device data</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-7">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">
                Browsers
            </h2>

            <div class="space-y-2.5">
                @forelse($stats['browsers'] ?? [] as $row)
                    <div class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gradient-to-r from-white to-gray-50/50 hover:border-gray-200 hover:shadow-xs transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                                @if(str_contains(strtolower($row->browser ?? ''), 'chrome'))
                                    🔴
                                @elseif(str_contains(strtolower($row->browser ?? ''), 'firefox'))
                                    🦊
                                @elseif(str_contains(strtolower($row->browser ?? ''), 'safari'))
                                    🍎
                                @elseif(str_contains(strtolower($row->browser ?? ''), 'edge'))
                                    🌐
                                @else
                                    🌍
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $row->browser ?? 'Unknown' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-900">
                                {{ $row->total }}
                            </span>
                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                            🌐
                        </div>
                        <p class="text-sm text-gray-400">No browser data</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 shadow-xs p-5 sm:p-7">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">
            Referrers
        </h2>

        <div class="space-y-2.5">
            @forelse($stats['referrers'] ?? [] as $row)
                <div class="group flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gradient-to-r from-white to-gray-50/50 hover:border-gray-200 hover:shadow-xs transition-all duration-200">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 flex-shrink-0">
                            🔗
                        </div>
                        <span class="text-sm text-gray-700 truncate">
                            {{ $row->referrer ?: 'Direct / Unknown' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-sm font-bold text-gray-900">
                            {{ $row->total }}
                        </span>
                        <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                        🔗
                    </div>
                    <p class="text-sm text-gray-400">No referrer data</p>
                </div>
            @endforelse
        </div>
    </div>

</div>