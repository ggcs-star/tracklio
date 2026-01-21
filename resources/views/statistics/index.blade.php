@extends('layouts.index')
@section('title','Analytics Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-8">

  {{-- ENHANCED HEADER WITH MODERN GRADIENT --}}
  <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-6 md:p-8 shadow-2xl mb-6 overflow-hidden relative">
    {{-- Animated gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 via-purple-500/10 to-cyan-500/20 animate-gradient-x"></div>
    
    <div class="relative z-10">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
        <div class="mb-6 md:mb-0">
          <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <div>
              <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 tracking-tight">Analytics Dashboard</h1>
              <p class="text-slate-300 text-lg">Real-time insights and performance metrics</p>
            </div>
          </div>
          
          {{-- Mini stats --}}
          <div class="flex flex-wrap items-center gap-4 text-sm">
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/10">
              <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
              <span class="text-slate-200 font-medium">Live Data Streaming</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-slate-300">Updated in real-time</span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="relative group">
            <form method="GET" action="">
              <select name="range" onchange="this.form.submit()"
                      class="appearance-none bg-slate-800/60 backdrop-blur-sm border border-slate-700 text-white rounded-xl pl-4 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 w-full md:w-48 hover:bg-slate-800/80 hover:border-slate-600">
                <option value="7"  {{ $days==7?'selected':'' }}>Last 7 Days</option>
                <option value="30" {{ $days==30?'selected':'' }}>Last 30 Days</option>
                <option value="90" {{ $days==90?'selected':'' }}>Last 90 Days</option>
              </select>
            </form>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>
          </div>
          <button class="px-4 py-3 bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm border border-white/10 text-white rounded-xl transition-all duration-300 hover:scale-105 hover:bg-gradient-to-r hover:from-blue-600/30 hover:to-purple-600/30 group">
            <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- ENHANCED KPI METRICS WITH BETTER COLORS --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    
    {{-- TOTAL CLICKS --}}
    <div class="group bg-gradient-to-br from-white to-blue-50/30 rounded-2xl border border-blue-100 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-cyan-500"></div>
      <div class="flex items-start justify-between mb-4">
        <div>
          <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Total Links</p>
          <p class="text-3xl font-bold text-slate-900">{{ number_format($totalLinksCount) }}</p>
        </div>
        <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
        </div>
      </div>
      <div class="pt-4 border-t border-blue-100/50">
        <div class="flex items-center justify-between text-sm">
          <span class="text-slate-600">Total QR + Links created</span>
          <span class="text-blue-600 font-semibold bg-blue-100 px-2 py-1 rounded-lg">100%</span>
        </div>
        <div class="w-full bg-blue-100 rounded-full h-2 mt-3">
          <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
      </div>
    </div>

    {{-- QR SCANS --}}
    <div class="group bg-gradient-to-br from-white to-purple-50/30 rounded-2xl border border-purple-100 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 to-fuchsia-500"></div>
      <div class="flex items-start justify-between mb-4">
        <div>
          <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">QR Scans</p>
          <p class="text-3xl font-bold text-slate-900">{{ number_format($totalQrScans) }}</p>
        </div>
        <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
          </svg>
        </div>
      </div>
      <div class="pt-4 border-t border-purple-100/50">
        <div class="flex items-center justify-between text-sm">
          <span class="text-slate-600">Scan rate</span>
          <span class="text-purple-600 font-semibold bg-purple-100 px-2 py-1 rounded-lg">
            {{ $totalLinksCount > 0 ? round(($totalQrScans / $totalLinksCount) * 100, 1) : 0 }}%
          </span>
        </div>
        <div class="w-full bg-purple-100 rounded-full h-2 mt-3">
          <div class="bg-gradient-to-r from-purple-600 to-fuchsia-500 h-2 rounded-full transition-all duration-1000" 
               style="width: {{ $totalInteractions > 0 ? ($totalQrScans / $totalInteractions) * 100 : 0 }}%"></div>
        </div>
      </div>
    </div>

    {{-- LINK CLICKS --}}
    <div class="group bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl border border-emerald-100 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-600 to-green-500"></div>
      <div class="flex items-start justify-between mb-4">
        <div>
          <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Link Clicks</p>
          <p class="text-3xl font-bold text-slate-900">
            {{ number_format($totalLinkClicks) }}
          </p>
        </div>
        <div class="p-3 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
          </svg>
        </div>
      </div>
      <div class="pt-4 border-t border-emerald-100/50">
        <div class="flex items-center justify-between text-sm">
          <span class="text-slate-600">Direct clicks</span>
          <span class="text-emerald-600 font-semibold bg-emerald-100 px-2 py-1 rounded-lg">
            {{ $totalInteractions > 0 
                ? round(($totalLinkClicks / $totalInteractions) * 100, 1) 
                : 0 
            }}%
          </span>
        </div>
        <div class="w-full bg-emerald-100 rounded-full h-2 mt-3">
          <div
            class="bg-gradient-to-r from-emerald-600 to-green-500 h-2 rounded-full transition-all duration-1000"
            style="width: {{
              $totalInteractions > 0
                ? ($totalLinkClicks / $totalInteractions) * 100
                : 0
            }}%">
          </div>
        </div>
      </div>
    </div>

    {{-- COUNTRIES --}}
    <div class="group bg-gradient-to-br from-white to-amber-50/30 rounded-2xl border border-amber-100 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 p-6 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-600 to-orange-500"></div>
      <div class="flex items-start justify-between mb-4">
        <div>
          <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Countries</p>
          <p class="text-3xl font-bold text-slate-900">{{ number_format($countries->count()) }}</p>
        </div>
        <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-sm">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <div class="pt-4 border-t border-amber-100/50">
        <div class="flex items-center justify-between text-sm">
          <span class="text-slate-600">Global reach</span>
          <span class="text-amber-600 font-semibold bg-amber-100 px-2 py-1 rounded-lg">
            {{ $countries->count() > 10 ? 'High' : ($countries->count() > 5 ? 'Medium' : 'Low') }}
          </span>
        </div>
        <div class="w-full bg-amber-100 rounded-full h-2 mt-3">
          <div class="bg-gradient-to-r from-amber-600 to-orange-500 h-2 rounded-full transition-all duration-1000"
               style="width: {{ $countries->count() > 0 ? min(($countries->count() / 20) * 100, 100) : 0 }}%"></div>
        </div>
      </div>
    </div>

  </div>

  {{-- ENHANCED PERFORMANCE CHART WITH BETTER COLORS --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Performance Trends
          </h3>
          <p class="text-slate-600 mt-1">Daily click patterns and engagement metrics</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg">
            <div class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-600 to-cyan-500"></div>
            <span class="text-sm font-medium text-slate-700">Total Clicks</span>
          </div>
          <button class="text-sm text-slate-600 hover:text-slate-900 px-4 py-2 border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Data
          </button>
        </div>
      </div>
    </div>
    <div class="p-6">
      <div class="h-80">
        <canvas id="clickChart"></canvas>
      </div>
    </div>
  </div>

  {{-- ENHANCED INSIGHTS SECTION --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- GEOGRAPHIC DISTRIBUTION --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
      <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
              </svg>
              Geographic Distribution
            </h3>
            <p class="text-slate-600 mt-1">Top countries by engagement</p>
          </div>
          <div class="text-sm text-slate-600 bg-blue-50 px-3 py-1.5 rounded-lg font-medium">
            {{ $countries->count() }} Countries
          </div>
        </div>
      </div>
      
      <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="h-72">
          <canvas id="countryChart"></canvas>
        </div>
        
        <div>
          <div class="space-y-4">
            @if($countries->count() > 0)
              @foreach($countries->take(6) as $country => $count)
              <div class="group flex items-center justify-between p-4 rounded-xl hover:bg-blue-50/50 transition-all duration-200 hover:scale-[1.02] border border-slate-100 hover:border-blue-200">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center group-hover:from-blue-200 group-hover:to-blue-100 transition-colors shadow-sm">
                    <span class="text-lg font-bold text-blue-700">{{ substr($country ?: '??', 0, 2) }}</span>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-900">{{ $country ?: 'Unknown' }}</p>
                    <p class="text-sm text-slate-500">{{ $count }} clicks</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold text-slate-900">{{ $count }}</p>
                  <p class="text-sm text-blue-600 font-medium bg-blue-50 px-2 py-1 rounded-lg">
                    {{ $totalInteractions > 0 ? round(($count / $totalInteractions) * 100, 1) : 0 }}%
                  </p>
                </div>
              </div>
              @endforeach
            @else
              <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                  <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <p class="text-slate-500">No geographic data available yet</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- DEVICE & BROWSER INSIGHTS --}}
    <div class="space-y-6">
      {{-- DEVICE USAGE --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Device Usage
              </h3>
              <p class="text-slate-600 text-sm mt-1">Platform distribution</p>
            </div>
          </div>
        </div>
        <div class="p-6">
          <div class="h-48 mb-6">
            <canvas id="deviceChart"></canvas>
          </div>
          @if($devices->count() > 0)
            <div class="space-y-3">
              @foreach($devices->take(4) as $device => $count)
              <div class="flex items-center justify-between p-3 rounded-lg hover:bg-emerald-50/50 transition-colors">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 rounded-full bg-gradient-to-r from-emerald-500 to-green-500"></div>
                  <span class="text-sm text-slate-700">{{ $device ?: 'Unknown' }}</span>
                </div>
                <div class="text-right">
                  <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                  <span class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded ml-2">
                    {{ $totalInteractions > 0 ? round(($count / $totalInteractions) * 100, 1) : 0 }}%
                  </span>
                </div>
              </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      {{-- BROWSER SHARE --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                </svg>
                Browser Share
              </h3>
              <p class="text-slate-600 text-sm mt-1">Browser usage analysis</p>
            </div>
          </div>
        </div>
        <div class="p-6">
          <div class="h-48 mb-6">
            <canvas id="browserChart"></canvas>
          </div>
          @if($browsers->count() > 0)
            <div class="space-y-3">
              @foreach($browsers->take(4) as $browser => $count)
              <div class="flex items-center justify-between p-3 rounded-lg hover:bg-purple-50/50 transition-colors">
                <div class="flex items-center gap-3">
                  <div class="w-2 h-2 rounded-full bg-gradient-to-r from-purple-500 to-fuchsia-500"></div>
                  <span class="text-sm text-slate-700">{{ $browser ?: 'Unknown' }}</span>
                </div>
                <div class="text-right">
                  <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                  <span class="text-xs text-purple-600 font-medium bg-purple-50 px-2 py-0.5 rounded ml-2">
                    {{ $totalInteractions > 0 ? round(($count / $totalInteractions) * 100, 1) : 0 }}%
                  </span>
                </div>
              </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>

</div>

<script>
// Enhanced Charts with Better Colors
const createModernLineChart = () => {
  const ctx = document.getElementById('clickChart').getContext('2d');
  
  return new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($clicksByDate->keys()) !!},
      datasets: [{
        label: 'Total Clicks',
        data: {!! json_encode($clicksByDate->values()) !!},
        borderColor: '#2563eb',
        backgroundColor: (context) => {
          const chart = context.chart;
          const {ctx, chartArea} = chart;
          if (!chartArea) return null;
          
          const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
          gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
          gradient.addColorStop(0.5, 'rgba(37, 99, 235, 0.25)');
          gradient.addColorStop(1, 'rgba(37, 99, 235, 0.05)');
          return gradient;
        },
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#2563eb',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 3,
        pointRadius: 5,
        pointHoverRadius: 8,
        pointHoverBackgroundColor: '#1d4ed8',
        pointHoverBorderColor: '#ffffff',
        pointHoverBorderWidth: 4,
        cubicInterpolationMode: 'monotone'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(15, 23, 42, 0.95)',
          titleColor: '#f8fafc',
          bodyColor: '#f8fafc',
          titleFont: {
            size: 14,
            family: "'Inter', sans-serif",
            weight: '600'
          },
          bodyFont: {
            size: 13,
            family: "'Inter', sans-serif"
          },
          padding: 12,
          cornerRadius: 8,
          borderColor: 'rgba(255, 255, 255, 0.15)',
          borderWidth: 1,
          displayColors: false,
          callbacks: {
            label: function(context) {
              return `📊 Clicks: ${context.parsed.y}`;
            },
            title: function(tooltipItems) {
              return `Date: ${tooltipItems[0].label}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(226, 232, 240, 0.6)',
            drawBorder: false,
            lineWidth: 1
          },
          ticks: {
            font: {
              size: 12,
              family: "'Inter', sans-serif"
            },
            color: '#64748b',
            padding: 10,
            callback: function(value) {
              return value.toLocaleString();
            }
          },
          border: {
            display: false
          }
        },
        x: {
          grid: {
            color: 'rgba(226, 232, 240, 0.4)',
            drawBorder: false,
            lineWidth: 1
          },
          ticks: {
            font: {
              size: 11,
              family: "'Inter', sans-serif"
            },
            color: '#64748b',
            maxRotation: 45,
            padding: 8
          },
          border: {
            display: false
          }
        }
      },
      interaction: {
        intersect: false,
        mode: 'index'
      },
      animations: {
        tension: {
          duration: 1000,
          easing: 'easeOutQuart'
        },
        radius: {
          duration: 400,
          easing: 'linear'
        }
      }
    }
  });
};

// Enhanced Donut Charts with PAIR-WISE Gradient Colors
const createEnhancedDonutChart = (id, labels, data, colorPairs) => {
  const ctx = document.getElementById(id).getContext('2d');
  
  // Create gradient effects for each slice using color pairs
  const gradientColors = colorPairs.map((pair, index) => {
    const gradient = ctx.createRadialGradient(100, 100, 0, 100, 100, 120);
    gradient.addColorStop(0, pair[0]); // Light color
    gradient.addColorStop(1, pair[1]); // Dark color
    return gradient;
  });

  const hoverColors = colorPairs.map(pair => {
    return Chart.helpers.color(pair[0]).lighten(0.2).rgbString();
  });

  return new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: gradientColors,
        borderWidth: 0,
        hoverBackgroundColor: hoverColors,
        hoverOffset: 25,
        borderRadius: 12,
        spacing: 8,
        borderAlign: 'inner',
        shadowColor: 'rgba(0, 0, 0, 0.1)',
        shadowBlur: 10,
        shadowOffsetX: 2,
        shadowOffsetY: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '68%',
      radius: '90%',
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(15, 23, 42, 0.95)',
          titleColor: '#f8fafc',
          bodyColor: '#f8fafc',
          padding: 14,
          cornerRadius: 10,
          borderColor: 'rgba(255, 255, 255, 0.15)',
          borderWidth: 1,
          boxPadding: 6,
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} clicks (${percentage}%)`;
            }
          }
        }
      },
      animation: {
        animateScale: true,
        animateRotate: true,
        duration: 1200,
        easing: 'easeOutQuart',
        onProgress: function(animation) {
          const chart = animation.chart;
          chart.update();
        }
      }
    }
  });
};

// PAIR-WISE Color Palettes (Light to Dark Gradients)
const pairWiseColorPalettes = {
  countryChart: [
    ['#60a5fa', '#1d4ed8'], // Blue pair
    ['#34d399', '#059669'], // Emerald pair
    ['#a78bfa', '#7c3aed'], // Purple pair
    ['#fbbf24', '#d97706'], // Amber pair
    ['#f87171', '#dc2626'], // Red pair
    ['#22d3ee', '#0d9488'], // Cyan pair
    ['#fb923c', '#ea580c'], // Orange pair
    ['#a3e635', '#65a30d'], // Lime pair
    ['#f472b6', '#db2777'], // Pink pair
    ['#818cf8', '#4f46e5']  // Indigo pair
  ],
  deviceChart: [
    ['#34d399', '#059669'], // Emerald pair
    ['#60a5fa', '#1d4ed8'], // Blue pair
    ['#fbbf24', '#d97706'], // Amber pair
    ['#a78bfa', '#7c3aed'], // Purple pair
    ['#f87171', '#dc2626'], // Red pair
    ['#22d3ee', '#0d9488']  // Cyan pair
  ],
  browserChart: [
    ['#a78bfa', '#7c3aed'], // Purple pair
    ['#60a5fa', '#1d4ed8'], // Blue pair
    ['#34d399', '#059669'], // Emerald pair
    ['#fbbf24', '#d97706'], // Amber pair
    ['#f87171', '#dc2626'], // Red pair
    ['#22d3ee', '#0d9488']  // Cyan pair
  ]
};

// Function to get appropriate color pairs based on data length
const getColorPairs = (chartType, dataLength) => {
  const basePalette = pairWiseColorPalettes[chartType];
  if (dataLength <= basePalette.length) {
    return basePalette.slice(0, dataLength);
  }
  // If more data items than colors, repeat the palette
  const repeatedPalette = [];
  for (let i = 0; i < dataLength; i++) {
    repeatedPalette.push(basePalette[i % basePalette.length]);
  }
  return repeatedPalette;
};

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
  // Create line chart
  const clickChart = createModernLineChart();
  
  // Get data lengths
  const countryDataLength = {!! json_encode($countries->keys()) !!}.length;
  const deviceDataLength = {!! json_encode($devices->keys()) !!}.length;
  const browserDataLength = {!! json_encode($browsers->keys()) !!}.length;

  // Create donut charts
  const countryChart = createEnhancedDonutChart(
    'countryChart',
    {!! json_encode($countries->keys()) !!},
    {!! json_encode($countries->values()) !!},
    getColorPairs('countryChart', countryDataLength)
  );

  const deviceChart = createEnhancedDonutChart(
    'deviceChart',
    {!! json_encode($devices->keys()) !!},
    {!! json_encode($devices->values()) !!},
    getColorPairs('deviceChart', deviceDataLength)
  );

  const browserChart = createEnhancedDonutChart(
    'browserChart',
    {!! json_encode($browsers->keys()) !!},
    {!! json_encode($browsers->values()) !!},
    getColorPairs('browserChart', browserDataLength)
  );

  // Enhanced chart hover effects with glow
  document.querySelectorAll('canvas').forEach(canvas => {
    canvas.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.03) rotate(1deg)';
      this.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
      this.style.filter = 'drop-shadow(0 12px 24px rgba(0, 0, 0, 0.15)) brightness(1.05)';
    });
    
    canvas.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1) rotate(0deg)';
      this.style.filter = 'drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1))';
    });
  });

  // Add rotation animation on page load for donut charts
  setTimeout(() => {
    const donutCharts = ['countryChart', 'deviceChart', 'browserChart'];
    donutCharts.forEach((chartId, index) => {
      const chart = document.getElementById(chartId);
      if (chart) {
        chart.style.transition = 'transform 1.2s ease-out';
        chart.style.transform = `scale(1) rotate(${index * 5}deg)`;
        setTimeout(() => {
          chart.style.transform = 'scale(1) rotate(0deg)';
        }, 1200);
      }
    });
  }, 500);
});
</script>

<style>
/* Animated gradient for header */
@keyframes gradient-x {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.animate-gradient-x {
  animation: gradient-x 3s ease infinite alternate;
}

/* Enhanced donut chart specific styles */
.donut-container {
  position: relative;
  overflow: visible;
}

.donut-container::after {
  content: '';
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  border-radius: 50%;
  background: radial-gradient(circle at center, 
    rgba(59, 130, 246, 0.1) 0%,
    rgba(59, 130, 246, 0.05) 40%,
    transparent 70%);
  z-index: -1;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.donut-container:hover::after {
  opacity: 1;
}

/* Donut slice hover animation */
@keyframes sliceHover {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1.03);
  }
}

.chartjs-render-monitor {
  animation: sliceHover 0.6s ease-out;
}

/* Responsive donut charts */
@media (max-width: 768px) {
  .donut-container {
    max-width: 250px;
    margin: 0 auto;
  }
  
  #countryChart,
  #deviceChart,
  #browserChart {
    max-height: 200px !important;
  }
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>
@endsection