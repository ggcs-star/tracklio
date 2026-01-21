@extends('layouts.index')

@section('title', 'QR & Links')

@section('content')

<div
    x-data="{
        openModal: false,
        editMode: false,
        editId: null,
        originalUrl: '',
        label: ''
    }"
    class="space-y-4 sm:space-y-6 md:space-y-8 p-3 sm:p-4 md:p-6"
>

    {{-- ================= ENHANCED HEADER WITH GRADIENT ================= --}}
    <div class="bg-gradient-to-br from-gray-900 via-slate-900 to-gray-950 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 shadow-lg sm:shadow-xl md:shadow-2xl overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-cyan-500/10 animate-gradient-x"></div>
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4 md:gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 md:gap-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 lg:w-14 md:h-12 lg:h-14 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg sm:rounded-xl flex items-center justify-center shadow-md sm:shadow-lg">
                        <i data-lucide="qr-code" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 lg:w-7 md:h-6 lg:h-7 text-white"></i>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-white mb-1 sm:mb-2">QR & Links Manager</h1>
                        <p class="text-slate-300 text-xs sm:text-sm md:text-base">Create, manage, and track QR codes with real-time analytics</p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="openModal = true; editMode = false; editId = null; originalUrl=''; label='';"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-2.5 md:py-3 rounded-lg sm:rounded-xl shadow-md sm:shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group flex items-center justify-center gap-1 sm:gap-2 md:gap-3 font-medium text-xs sm:text-sm md:text-base mt-4 lg:mt-0 w-full sm:w-auto">
                    <i data-lucide="plus" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 lg:w-5 md:h-5 lg:h-5 group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Create QR Code</span>
                </button>
            </div>

            {{-- Stats bar --}}
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 md:gap-4 mt-4 sm:mt-5 md:mt-6 lg:mt-8 pt-3 sm:pt-4 md:pt-5 lg:pt-6 border-t border-white/10">
                <div class="flex items-center gap-1 sm:gap-2">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs sm:text-sm text-slate-300">Real-time tracking</span>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xs sm:text-sm text-slate-300">Updated instantly</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= ENHANCED STATS GRID ================= --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
        @php
            $stats = [
                ['icon' => 'qr-code', 'color' => 'indigo', 'label' => 'Total QR Codes', 'value' => $totalQrs ?? 0],
                ['icon' => 'link-2', 'color' => 'purple', 'label' => 'Total Links', 'value' => $totalLinks ?? 0],
                ['icon' => 'scan-line', 'color' => 'blue', 'label' => 'QR Scans', 'value' => $totalQrScans ?? 0],
                ['icon' => 'mouse-pointer-click', 'color' => 'emerald', 'label' => 'Total Visits', 'value' => $totalVisits ?? 0],
                ['icon' => 'activity', 'color' => 'cyan', 'label' => 'Active QRs', 'value' => $activeQrs ?? 0],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="group bg-gradient-to-br from-white to-{{ $stat['color'] }}-50/30 rounded-lg sm:rounded-xl md:rounded-2xl border border-{{ $stat['color'] }}-100 shadow sm:shadow-md md:shadow-lg hover:shadow-lg md:hover:shadow-xl transition-all duration-500 hover:-translate-y-1 p-2 sm:p-3 md:p-4 lg:p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-{{ $stat['color'] }}-600 to-{{ $stat['color'] }}-500"></div>
            <div class="flex items-start justify-between mb-1 sm:mb-2 md:mb-3 lg:mb-4">
                <div class="min-w-0 pr-2">
                    <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 truncate">{{ $stat['label'] }}</p>
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl xl:text-3xl font-bold text-slate-900 truncate">{{ number_format($stat['value']) }}</p>
                </div>
                <div class="p-1 sm:p-1.5 md:p-2 lg:p-3 bg-gradient-to-br from-{{ $stat['color'] }}-100 to-{{ $stat['color'] }}-50 rounded-lg sm:rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-sm flex-shrink-0">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 lg:w-6 md:h-5 lg:h-6 text-{{ $stat['color'] }}-600"></i>
                </div>
            </div>
            <div class="pt-1 sm:pt-2 md:pt-3 lg:pt-4 border-t border-{{ $stat['color'] }}-100/50">
                <div class="w-full bg-{{ $stat['color'] }}-100 rounded-full h-1 sm:h-1.5 md:h-2 mt-1 sm:mt-2">
                    <div class="bg-gradient-to-r from-{{ $stat['color'] }}-600 to-{{ $stat['color'] }}-500 h-1 sm:h-1.5 md:h-2 rounded-full transition-all duration-1000" 
                         style="width: {{ $stat['value'] > 0 ? min(($stat['value'] / 1000) * 100, 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ================= ENHANCED MAIN CONTENT ================= --}}
    <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-md sm:shadow-lg md:shadow-xl border border-slate-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-3 md:py-4 lg:py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 sm:gap-3 md:gap-4">
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-slate-900 flex items-center gap-1 sm:gap-2 md:gap-3">
                        <i data-lucide="grid" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 lg:w-6 md:h-5 lg:h-6 text-indigo-600"></i>
                        <span class="truncate">QR Codes List</span>
                    </h3>
                    <p class="text-slate-600 text-xs sm:text-sm md:text-base mt-1 truncate">{{ count($qrs) }} QR codes created • Last updated just now</p>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-3 w-full md:w-auto mt-3 md:mt-0">
                    <form method="GET" action="{{ route('qr-links.index') }}" id="qrSearchForm" class="flex-1 md:flex-none md:w-40 lg:w-48 xl:w-64">
                        <div class="relative">
    <input type="text"
        id="qrSearchInput"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search..."
        class="pl-8 sm:pl-9 md:pl-10 pr-8 sm:pr-9 md:pr-10 py-1.5 sm:py-2 md:py-2.5 border border-slate-300 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm w-full transition-all duration-300 hover:border-slate-400">

    {{-- search icon --}}
    <i data-lucide="search"
       class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-slate-400 absolute left-2 sm:left-3 top-1/2 -translate-y-1/2"></i>

    {{-- ❌ clear icon --}}
    <button type="button"
        id="clearSearchBtn"
        class="hidden absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition">
        <i data-lucide="x-circle" class="w-4 h-4"></i>
    </button>
</div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ================= ENHANCED TABLE (Responsive) ================= --}}
        <div class="overflow-x-auto -mx-0 sm:mx-0">
            <div class="min-w-[700px] sm:min-w-0">
                <table class="w-full text-xs sm:text-sm">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                        <tr>
                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <i data-lucide="qr-code" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden xs:inline">QR Code</span>
                                </div>
                            </th>

                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <i data-lucide="link" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden sm:inline">Original URL</span>
                                </div>
                            </th>

                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <i data-lucide="external-link" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden sm:inline">Short Link</span>
                                </div>
                            </th>

                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <i data-lucide="mouse-pointer-click" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden sm:inline">Visits</span>
                                </div>
                            </th>

                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <i data-lucide="scan-line" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden sm:inline">QR Scans</span>
                                </div>
                            </th>

                            <th class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <i data-lucide="settings" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4"></i>
                                    <span class="hidden sm:inline">Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($qrs as $qr)
                        <tr class="group hover:bg-gradient-to-r hover:from-indigo-50/30 hover:to-purple-50/30 transition-all duration-200">

                            {{-- QR CODE IMAGE WITH AVATAR --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4">
                                <div class="flex items-center gap-1 sm:gap-2 md:gap-3 lg:gap-4">
                                    <div class="relative group/qr">
                                        @if($qr->qr_image_path && Storage::disk('public')->exists($qr->qr_image_path))
                                            <img
                                                src="{{ Storage::disk('public')->url($qr->qr_image_path) }}?v={{ $qr->updated_at?->timestamp ?? time() }}"
                                                class="w-8 h-8 sm:w-10 sm:h-10 md:w-14 lg:w-20 md:h-14 lg:h-20 rounded-lg sm:rounded-xl md:rounded-2xl border border-slate-200 sm:border-2 group-hover/qr:border-indigo-300 transition-all duration-300 shadow-md sm:shadow-lg group-hover/qr:shadow-xl group-hover/qr:scale-105"
                                                alt="QR Code"
                                                onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAiIGhlaWdodD0iODAiIHJ4PSIxMiIgZmlsbD0iI0YxRjVGMTIvNjAiLz48cGF0aCBkPSJNMjggMjhIMzJWMzJIMjhWMjhaTTQ4IDI4SDUyVjMySDQ4VjI4Wk0zOCA0OEg0MlY1MkgzOFY0OFpNNTggMjhINjJWMzJINThWMjhaTTI4IDM4SDMyVjQySDI4VjM4Wk0yOCA0OEgzMlY1MkgyOFY0OFpNMzggMzhINDJWNDJIMzhWMzhaIiBmaWxsPSIjN0Y4Q0FGIi8+PHJlY3QgeD0iMjgiIHk9IjU4IiB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjN0Y4Q0FGIi8+PHJlY3QgeD0iNTgiIHk9IjM4IiB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjN0Y4Q0FGIi8+PC9zdmc+'; showToast('QR image failed to load', 'error')">
                                        @else
                                            {{-- Placeholder for QR code that hasn't been generated yet --}}
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-14 lg:w-20 md:h-14 lg:h-20 rounded-lg sm:rounded-xl md:rounded-2xl border border-slate-200 sm:border-2 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                                <i data-lucide="qr-code" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 lg:w-8 md:h-5 lg:h-8 text-slate-400"></i>
                                            </div>
                                        @endif
                                        <div class="absolute -top-1 -right-1 sm:-top-1 sm:-right-1 md:-top-1.5 md:-right-1.5 lg:-top-2 lg:-right-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[8px] sm:text-[10px] md:text-xs px-1 py-0.5 sm:px-1.5 sm:py-0.5 md:px-2 md:py-1 lg:px-2.5 lg:py-1 rounded-full font-medium shadow">
                                            QR
                                        </div>
                                    </div>
                                    <div class="text-[10px] sm:text-xs text-slate-500 hidden sm:block">
                                        {{ Carbon\Carbon::parse($qr->created_at)->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>

                            {{-- ORIGINAL URL --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4">
                                <div class="max-w-[80px] sm:max-w-[100px] md:max-w-xs lg:max-w-md min-w-0">
                                    <p class="text-slate-900 font-medium truncate group-hover:text-indigo-700 transition-colors text-xs sm:text-sm md:text-base" title="{{ $qr->original_url }}">
                                        {{ Str::limit($qr->original_url, 15) }}
                                    </p>
                                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 flex items-center gap-1 truncate">
                                        <i data-lucide="globe" class="w-2.5 h-2.5 sm:w-3 sm:h-3 flex-shrink-0"></i>
                                        @if($qr->original_url)
                                            {{ parse_url($qr->original_url, PHP_URL_HOST) }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                    @if($qr->label)
                                    <span class="inline-block mt-1 sm:mt-1.5 md:mt-2 px-1 py-0.5 sm:px-1.5 sm:py-0.5 md:px-2 md:py-1 lg:px-3 lg:py-1.5 bg-gradient-to-r from-indigo-100 to-indigo-50 text-indigo-700 text-[10px] sm:text-xs font-medium rounded-full border border-indigo-200 truncate max-w-full">
                                        <i data-lucide="tag" class="w-2 h-2 sm:w-2.5 sm:h-2.5 md:w-3 md:h-3 inline mr-0.5 sm:mr-1"></i>
                                        {{ Str::limit($qr->label, 10) }}
                                    </span>
                                    @endif
                                </div>
                            </td>

                            {{-- SHORT LINK WITH COPY --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4">
                                <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 lg:gap-3 min-w-0">
                                    <a href="{{ url('/q/'.$qr->short_code) }}" 
                                       target="_blank"
                                       class="text-xs sm:text-sm md:text-base truncate max-w-[60px] sm:max-w-[80px] md:max-w-[120px] lg:max-w-[180px] hover:text-indigo-600 transition-colors">
                                        {{ url('/q/'.$qr->short_code) }}
                                    </a>
                                    <button
                                        type="button"
                                        onclick="copyToClipboard('{{ url('/q/'.$qr->short_code) }}')"
                                        class="text-slate-400 hover:text-indigo-600 p-1 sm:p-1 md:p-1.5 lg:p-2 hover:bg-indigo-50 rounded-lg sm:rounded-xl transition-all duration-200 hover:scale-110 flex-shrink-0"
                                        title="Copy link">
                                        <i data-lucide="copy" class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-4 lg:h-4"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- VISITS WITH PROGRESS --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-slate-900">{{ $qr->visit_count }}</span>
                                    <div class="w-6 sm:w-8 md:w-10 lg:w-12 xl:w-16 h-1 sm:h-1.5 md:h-2 bg-slate-200 rounded-full overflow-hidden mt-0.5 sm:mt-1 md:mt-2">
                                        <div class="h-full bg-gradient-to-r from-emerald-500 to-green-500 rounded-full transition-all duration-1000" 
                                             style="width: {{ $qr->visit_count > 0 ? min(($qr->visit_count / max(1, $qrs->max('visit_count'))) * 100, 100) : 0 }}%"></div>
                                    </div>
                                    <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5 sm:mt-1">Visits</p>
                                </div>
                            </td>

                            {{-- QR SCANS WITH PROGRESS --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-indigo-600">{{ $qr->qr_scan_count ?? 0 }}</span>
                                    <div class="w-6 sm:w-8 md:w-10 lg:w-12 xl:w-16 h-1 sm:h-1.5 md:h-2 bg-slate-200 rounded-full overflow-hidden mt-0.5 sm:mt-1 md:mt-2">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all duration-1000" 
                                             style="width: {{ ($qr->qr_scan_count ?? 0) > 0 ? min((($qr->qr_scan_count ?? 0) / max(1, $qrs->max('qr_scan_count'))) * 100, 100) : 0 }}%"></div>
                                    </div>
                                    <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5 sm:mt-1">Scans</p>
                                </div>
                            </td>

                            {{-- ENHANCED ACTION BUTTONS --}}
                            <td class="px-2 sm:px-3 md:px-4 lg:px-6 py-1.5 sm:py-2 md:py-3 lg:py-4 text-right">
                                <div class="inline-flex items-center gap-0.5 sm:gap-1 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg sm:rounded-xl p-0.5 sm:p-1 md:p-1">

                                    {{-- VIEW --}}
                                    <a href="{{ route('qr-links.show', $qr->_id) }}"
                                       class="p-1 sm:p-1.5 md:p-2 lg:p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md sm:rounded-lg transition-all duration-200 hover:scale-110 group/action"
                                       title="View details">
                                        <i data-lucide="eye" class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-4 lg:h-4 group-hover/action:rotate-12 transition-transform"></i>
                                    </a>

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        @click="
                                            openModal = true;
                                            editMode = true;
                                            editId = '{{ $qr->_id }}';
                                            originalUrl = '{{ addslashes($qr->original_url) }}';
                                            label = '{{ addslashes($qr->label) }}';
                                        "
                                        class="p-1 sm:p-1.5 md:p-2 lg:p-2.5 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-md sm:rounded-lg transition-all duration-200 hover:scale-110 group/action"
                                        title="Edit">
                                        <i data-lucide="edit-3" class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-4 lg:h-4 group-hover/action:rotate-12 transition-transform"></i>
                                    </button>

                                    {{-- DOWNLOAD --}}
                                    <button
                                        type="button"
                                        onclick="downloadPngFromSvg('{{ Storage::disk('public')->url($qr->qr_image_path) }}', '{{ $qr->short_code }}')"
                                        class="p-1 sm:p-1.5 md:p-2 lg:p-2.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md sm:rounded-lg transition-all duration-200 hover:scale-110 group/action"
                                        title="Download PNG">
                                        <i data-lucide="download" class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-4 lg:h-4 group-hover/action:rotate-12 transition-transform"></i>
                                    </button>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('qr-links.destroy', $qr->_id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this QR code? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1 sm:p-1.5 md:p-2 lg:p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md sm:rounded-lg transition-all duration-200 hover:scale-110 group/action"
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-4 lg:h-4 group-hover/action:rotate-12 transition-transform"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 sm:px-4 md:px-5 lg:px-6 py-6 sm:py-8 md:py-10 lg:py-12 xl:py-20 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto px-2">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-16 lg:w-24 md:h-16 lg:h-24 bg-gradient-to-br from-slate-100 to-slate-50 rounded-lg sm:rounded-xl md:rounded-2xl flex items-center justify-center mb-2 sm:mb-3 md:mb-4 lg:mb-6 shadow-md sm:shadow-lg">
                                        <i data-lucide="qr-code" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 lg:w-8 md:h-6 lg:h-8 lg:w-12 lg:h-12 text-slate-400"></i>
                                    </div>
                                    <h3 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl font-bold text-slate-900 mb-1 sm:mb-2 md:mb-3">No QR Codes Yet</h3>
                                    <p class="text-slate-600 text-center mb-3 sm:mb-4 md:mb-5 lg:mb-8 text-xs sm:text-sm md:text-base">
                                        Start by creating your first QR code. Track scans, visits, and analytics all in one place.
                                    </p>
                                    <button
                                        type="button"
                                        @click="openModal = true; editMode = false; editId = null; originalUrl=''; label='';"
                                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-3 py-2 sm:px-4 sm:py-2.5 md:px-5 lg:px-8 md:py-3 lg:py-4 rounded-lg sm:rounded-xl font-medium shadow-md sm:shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group text-xs sm:text-sm md:text-base w-full sm:w-auto">
                                        <i data-lucide="plus" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 lg:w-5 md:h-4 lg:h-5 mr-1 sm:mr-2 inline group-hover:rotate-90 transition-transform"></i>
                                        Create Your First QR Code
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

      @if ($qrs->hasPages())
<div class="px-3 sm:px-4 md:px-5 lg:px-6 py-3 sm:py-4 border-t border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs sm:text-sm text-slate-600">

        {{-- LEFT: info --}}
        <div>
            Showing
            <span class="font-semibold text-slate-900">{{ $qrs->firstItem() }}</span>
            –
            <span class="font-semibold text-slate-900">{{ $qrs->lastItem() }}</span>
            of
            <span class="font-semibold text-slate-900">{{ $qrs->total() }}</span>
            QR codes
        </div>

        {{-- RIGHT: controls --}}
        <div class="flex items-center gap-1 sm:gap-2">
            {{-- Prev --}}
            <a href="{{ $qrs->previousPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg sm:rounded-xl border
               {{ $qrs->onFirstPage() ? 'text-slate-300 border-slate-200 pointer-events-none' : 'border-slate-300 hover:bg-slate-50' }}">
                ‹
            </a>

            {{-- Pages --}}
            @foreach ($qrs->getUrlRange(1, $qrs->lastPage()) as $page => $url)
                @if ($page == $qrs->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg sm:rounded-xl bg-indigo-600 text-white font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg sm:rounded-xl border border-slate-300 hover:bg-slate-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            <a href="{{ $qrs->nextPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg sm:rounded-xl border
               {{ $qrs->hasMorePages() ? 'border-slate-300 hover:bg-slate-50' : 'text-slate-300 border-slate-200 pointer-events-none' }}">
                ›
            </a>
        </div>
    </div>
</div>
@endif

    </div>

    {{-- ================= ENHANCED MODAL ================= --}}
    <div
        x-show="openModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-3 md:p-4 bg-black/60 backdrop-blur-sm">

        <div
            x-show="openModal"
            @click.away="openModal = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white w-full max-w-xs sm:max-w-sm md:max-w-md rounded-lg sm:rounded-xl md:rounded-2xl shadow-xl md:shadow-2xl transform transition-all overflow-hidden max-h-[90vh] overflow-y-auto mx-2 sm:mx-3 md:mx-4">

            {{-- MODAL HEADER --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-3 sm:px-4 md:px-5 lg:px-8 py-3 sm:py-4 md:py-5 lg:py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 min-w-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 lg:w-12 md:h-10 lg:h-12 bg-white/20 backdrop-blur-sm rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="qr-code" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 lg:w-6 md:h-5 lg:h-6 text-white"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xs sm:text-sm md:text-base lg:text-lg font-bold text-white truncate"
                                x-text="editMode ? 'Edit QR Code' : 'Create QR Code'"></h2>
                            <p class="text-indigo-100 text-xs sm:text-sm mt-0.5 truncate"
                               x-text="editMode ? 'Update your QR code details' : 'Generate a new QR code'"></p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="openModal = false"
                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 lg:w-10 md:h-8 lg:h-10 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 rounded-lg sm:rounded-xl transition-colors flex-shrink-0 ml-1 sm:ml-2">
                        <i data-lucide="x" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 lg:w-5 md:h-4 lg:h-5"></i>
                    </button>
                </div>
            </div>

            <form
                :action="editMode
                    ? `/qr-links/${editId}/update`
                    : '{{ route('qr-links.store') }}'"
                method="POST"
                class="p-3 sm:p-4 md:p-5 lg:p-8 space-y-3 sm:space-y-4 md:space-y-5 lg:space-y-6">

                @csrf

                <div class="space-y-1.5 sm:space-y-2 md:space-y-3">
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                            <i data-lucide="link" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-indigo-500"></i>
                            <span>Destination URL</span>
                        </div>
                    </label>
                    <input
                        type="url"
                        name="original_url"
                        x-model="originalUrl"
                        required
                        placeholder="https://example.com/your-long-url"
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 md:py-3 border-2 border-slate-200 rounded-lg sm:rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-300 text-xs sm:text-sm md:text-base hover:border-slate-300">
                    <p class="text-xs text-slate-500">The URL this QR code will redirect to</p>
                </div>

                <div class="space-y-1.5 sm:space-y-2 md:space-y-3">
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700">
                        <div class="flex items-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                            <i data-lucide="tag" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-indigo-500"></i>
                            <span>Label (Optional)</span>
                        </div>
                    </label>
                    <input
                        type="text"
                        name="label"
                        x-model="label"
                        placeholder="e.g., Marketing Campaign, Event QR"
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 md:py-3 border-2 border-slate-200 rounded-lg sm:rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-300 text-xs sm:text-sm md:text-base hover:border-slate-300">
                    <p class="text-xs text-slate-500">Add a label to easily identify this QR code</p>
                </div>

                {{-- PREVIEW SECTION --}}
                <div x-show="originalUrl" class="pt-2 sm:pt-3 md:pt-4 border-t border-slate-100 space-y-1.5 sm:space-y-2 md:space-y-3">
                    <p class="text-xs sm:text-sm font-medium text-slate-700">Preview</p>
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg sm:rounded-xl p-2 sm:p-3 md:p-4 border border-slate-200">
                        <p class="text-xs sm:text-sm text-slate-600 truncate">
                            <span class="font-medium">URL:</span> 
                            <span x-text="originalUrl" class="truncate block"></span>
                        </p>
                        <p x-show="label" class="text-xs sm:text-sm text-slate-600 mt-1 sm:mt-2">
                            <span class="font-medium">Label:</span> 
                            <span x-text="label" class="truncate block"></span>
                        </p>
                    </div>
                </div>

                {{-- MODAL FOOTER --}}
                <div class="flex justify-end gap-2 sm:gap-3 md:gap-4 pt-3 sm:pt-4 md:pt-5 lg:pt-6 border-t border-slate-100">
                    <button
                        type="button"
                        @click="openModal = false"
                        class="px-2 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2.5 lg:px-6 lg:py-3 border-2 border-slate-300 text-slate-700 rounded-lg sm:rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-all duration-300 font-medium hover:scale-105 text-xs sm:text-sm md:text-base">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-2 py-1.5 sm:px-3 sm:py-2 md:px-4 md:py-2.5 lg:px-6 lg:py-3 rounded-lg sm:rounded-xl font-semibold shadow-md sm:shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group text-xs sm:text-sm md:text-base">
                        <span x-text="editMode ? 'Update QR Code' : 'Generate QR Code'"></span>
                        <i data-lucide="arrow-right" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 ml-1 sm:ml-2 inline group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- COPY TO CLIPBOARD FUNCTION --}}
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Link copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy. Please try again.', 'error');
    });
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) {
        lucide.createIcons();
    }
});

// Toast notification function - FIXED VISIBILITY
function showToast(message, type = 'info') {
    const colors = {
        success: 'from-emerald-500 to-green-500',
        error: 'from-rose-500 to-red-500',
        info: 'from-blue-500 to-cyan-500'
    };
    
    const icons = {
        success: 'check-circle',
        error: 'alert-circle',
        info: 'info'
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-3 sm:top-4 md:top-6 right-3 sm:right-4 md:right-6 z-[9999] px-3 py-2 sm:px-4 sm:py-3 md:px-5 md:py-4 rounded-lg sm:rounded-xl shadow-xl md:shadow-2xl text-white font-medium transform transition-all duration-300 translate-x-full ${colors[type]}`;
    
    toast.innerHTML = `
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 lg:w-10 md:h-8 lg:h-10 bg-white/30 backdrop-blur-sm rounded-md sm:rounded-lg flex items-center justify-center flex-shrink-0">
                <i data-lucide="${icons[type]}" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 lg:w-5 md:h-4 lg:h-5 text-white"></i>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-xs sm:text-sm md:text-base truncate" style="color: white !important;">${type.charAt(0).toUpperCase() + type.slice(1)}</p>
                <p class="text-xs sm:text-sm mt-0.5 line-clamp-2" style="color: rgba(255,255,255,0.95) !important;">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);
    
    // Initialize icon
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}
</script>

<style>
@keyframes gradient-x {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.animate-gradient-x {
    background-size: 200% 200%;
    animation: gradient-x 15s ease infinite;
}

/* Responsive table container */
@media (max-width: 640px) {
    .overflow-x-auto {
        margin-left: -0.25rem;
        margin-right: -0.25rem;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    .min-w-\[700px\] {
        min-width: 700px;
    }
}

/* Responsive breakpoints */
@media (min-width: 375px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Touch-friendly buttons on mobile */
@media (max-width: 768px) {
    button:not(.action-button),
    a[role="button"],
    .action-button {
        min-height: 32px;
        min-width: 32px;
    }
    
    .modal-button {
        min-height: 36px;
    }
}

/* Better table readability on mobile */
@media (max-width: 640px) {
    table td, table th {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    .action-buttons-container {
        display: flex;
        flex-wrap: nowrap;
    }
}

/* Prevent text overflow */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Modal responsive adjustments */
@media (max-width: 640px) {
    .modal-content {
        max-width: 95%;
        margin: 0 auto;
    }
}

/* Smooth scrolling for mobile */
html {
    -webkit-overflow-scrolling: touch;
}

/* Better tap highlight for mobile */
a, button {
    -webkit-tap-highlight-color: rgba(79, 70, 229, 0.2);
}

/* Hide/show elements based on screen size */
@media (max-width: 480px) {
    .hidden-xs {
        display: none !important;
    }
}

@media (max-width: 640px) {
    .hidden-sm {
        display: none !important;
    }
}

/* Progress bar responsive */
.progress-bar {
    transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

/* QR code image responsive */
.qr-image {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.qr-image:hover {
    transform: scale(1.05);
}

/* Action buttons hover effect */
.group\/action:hover i {
    animation: bounce 0.3s ease;
}

@keyframes bounce {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Smooth scrollbar for desktop */
@media (min-width: 768px) {
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #4f46e5, #7c3aed);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #4338ca, #6d28d9);
    }
}

/* Mobile-safe hover effects */
@media (hover: hover) and (pointer: fine) {
    .hover-effect:hover {
        transform: translateY(-1px);
    }
}

/* Loading states */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Safe area for notched phones */
@media (max-width: 768px) {
    body {
        padding-left: env(safe-area-inset-left, 0);
        padding-right: env(safe-area-inset-right, 0);
    }
}

/* Line clamp utility */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* TOAST VISIBILITY FIXES - NEW */
.fixed.z-\[9999\] {
    z-index: 9999 !important;
}

/* Ensure gradient backgrounds are visible */
.from-emerald-500.to-green-500 {
    background-image: linear-gradient(to right, #10b981, #22c55e) !important;
}

.from-rose-500.to-red-500 {
    background-image: linear-gradient(to right, #f43f5e, #ef4444) !important;
}

.from-blue-500.to-cyan-500 {
    background-image: linear-gradient(to right, #3b82f6, #06b6d4) !important;
}

/* Force white text in toasts */
.fixed.top-4.right-4.text-white,
.fixed.top-4.right-4.text-white *:not(i) {
    color: white !important;
}

/* Toast text visibility */
[class*="from-"].to-[class*="-500"] p,
[class*="from-"].to-[class*="-500"] span,
[class*="from-"].to-[class*="-500"] div:not([class*="bg-"]) {
    color: white !important;
}

/* Toast background opacity fix */
.bg-white\\/20 {
    background-color: rgba(255, 255, 255, 0.3) !important;
}

/* Toast shadow enhancement */
.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3) !important;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .space-y-4 {
        gap: 1rem !important;
    }
    
    .space-y-6 {
        gap: 1.5rem !important;
    }
    
    .space-y-8 {
        gap: 2rem !important;
    }
    
    .text-lg {
        font-size: 1rem !important;
    }
    
    .text-xl {
        font-size: 1.125rem !important;
    }
    
    .text-2xl {
        font-size: 1.25rem !important;
    }
    
    .text-3xl {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 768px) {
    .grid-cols-5 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 1024px) {
    .grid-cols-5 {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

/* Mobile-optimized table */
@media (max-width: 768px) {
    table {
        font-size: 0.75rem;
    }
    
    td, th {
        padding: 0.5rem 0.25rem;
    }
}

/* Ensure content fits in mobile viewport */
@media (max-width: 640px) {
    .p-4 {
        padding: 1rem !important;
    }
    
    .p-6 {
        padding: 1.5rem !important;
    }
    
    .p-8 {
        padding: 2rem !important;
    }
}

/* Touch-friendly input fields */
@media (max-width: 768px) {
    input, select, textarea {
        font-size: 16px !important;
        min-height: 44px;
    }
}

/* Mobile card adjustments */
@media (max-width: 640px) {
    .rounded-2xl {
        border-radius: 1rem !important;
    }
    
    .rounded-xl {
        border-radius: 0.75rem !important;
    }
    
    .rounded-lg {
        border-radius: 0.5rem !important;
    }
}
</style>

@push('scripts')
<script>
window.downloadPngFromSvg = function (svgUrl, filename) {
    // Show loading state
    showToast('Downloading QR Code...', 'info');
    
    fetch(svgUrl)
        .then(r => {
            if (!r.ok) {
                throw new Error('QR Code file not found');
            }
            return r.text();
        })
        .then(svg => {
            if (!svg.includes('<svg')) {
                throw new Error('Invalid QR Code format');
            }

            const img = new Image();
            const blob = new Blob([svg], { type: 'image/svg+xml' });
            const url = URL.createObjectURL(blob);

            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = 800;
                canvas.height = 800;

                const ctx = canvas.getContext('2d');
                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, 800, 800);
                ctx.drawImage(img, 0, 0, 800, 800);

                const a = document.createElement('a');
                a.href = canvas.toDataURL('image/png');
                a.download = `QR-Code-${filename}.png`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                URL.revokeObjectURL(url);
                
                // Success message
                showToast('QR Code downloaded successfully!', 'success');
            };

            img.onerror = () => {
                throw new Error('Failed to load QR Code image');
            };

            img.src = url;
        })
        .catch((error) => {
            console.error('Download error:', error);
            
            // User-friendly error message
            let errorMessage = 'Unable to download QR Code. ';
            
            if (error.message.includes('not found')) {
                errorMessage += 'The QR image is not available.';
            } else if (error.message.includes('Invalid QR')) {
                errorMessage += 'The QR image format is invalid.';
            } else if (error.message.includes('Failed to load')) {
                errorMessage += 'Failed to load the image.';
            } else {
                errorMessage += 'Please try again later.';
            }
            
            showToast(errorMessage, 'error');
        });
};
</script>
@endpush
<script>
let qrSearchTimer = null;

const input = document.getElementById('qrSearchInput');
const clearBtn = document.getElementById('clearSearchBtn');

input.addEventListener('input', function () {
    clearTimeout(qrSearchTimer);

    // ❌ icon show/hide
    clearBtn.classList.toggle('hidden', !this.value);

    qrSearchTimer = setTimeout(() => {
        fetchResults(this.value);
    }, 300);
});

clearBtn.addEventListener('click', function () {
    input.value = '';
    clearBtn.classList.add('hidden');

    fetchResults(''); // 🔥 load ALL data
});

function fetchResults(query) {
    const url = new URL(window.location.href);

    if (query) {
        url.searchParams.set('search', query);
    } else {
        url.searchParams.delete('search');
    }

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // replace only table
        document.querySelector('table').innerHTML =
            doc.querySelector('table').innerHTML;

        // update URL (no reload)
        history.replaceState(null, '', url);

        if (window.lucide) lucide.createIcons();
    });
}
</script>


@endsection