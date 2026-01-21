@extends('layouts.index')

@section('title','Short Links')

@section('content')

{{-- BALANCED HEADER --}}
<div class="mb-10">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <div class="flex items-center gap-4 mb-3">
                <div class="relative">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Short Links</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Create and manage your shortened URLs</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 sm:gap-4">
        <form method="GET" action="{{ route('short-links.index') }}" id="searchForm">
    <div class="relative hidden sm:block flex-1 max-w-xs">

        <input type="text"
               id="searchInput"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search links..."
               class="pl-10 pr-9 py-2.5 sm:py-3 border border-gray-300 rounded-xl
                      focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500
                      w-full max-w-xs">

        {{-- search icon --}}
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>

        {{-- ❌ clear icon --}}
        <button type="button"
                id="clearSearchBtn"
                class="hidden absolute right-3 top-1/2 -translate-y-1/2
                       text-gray-400 hover:text-red-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

    </div>
</form>


            <!-- <button class="bg-white border border-gray-300 hover:border-gray-400 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl transition-colors flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="text-xs sm:text-sm font-medium text-gray-700 hidden xs:inline">Filter</span>
            </button> -->
        </div>
    </div>
</div>

{{-- QUICK CREATE CARD --}}
<div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 mb-6 sm:mb-8">
    <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white border border-indigo-200 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <div class="min-w-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">Create Short Link</h2>
            <p class="text-xs sm:text-sm text-gray-600">Shorten your long URLs in seconds</p>
        </div>
    </div>
    
    <form action="{{ route('short-links.index') }}" method="POST" class="space-y-3 sm:space-y-4">
        @csrf
        
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <div class="flex-1">
                <input type="url"
                       name="original_url"
                       required
                       placeholder="Paste your long URL here..."
                       class="w-full border border-gray-300 rounded-xl px-4 sm:px-5 py-3 sm:py-4 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 placeholder-gray-500 text-sm sm:text-base bg-white">
            </div>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold shadow-sm hover:shadow transition-all duration-200 whitespace-nowrap flex items-center justify-center gap-2 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="hidden xs:inline">Shorten URL</span>
                <span class="xs:hidden">Shorten</span>
            </button>
        </div>
        
        @if(session('success'))
        <div class="mt-3 sm:mt-4 p-3 sm:p-4 bg-green-50 border border-green-200 rounded-xl flex items-start sm:items-center gap-2 sm:gap-3">
            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 sm:mt-0">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <p class="text-green-700 font-medium text-sm sm:text-base">{{ session('success') }}</p>
        </div>
        @endif
    </form>
</div>

{{-- STATS GRID --}}
<div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 hover:border-indigo-300 transition-colors">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-blue-50 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-gray-500">Total Links</p>
                <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mt-0.5 sm:mt-1">{{ count($links) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 hover:border-indigo-300 transition-colors">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-green-50 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-gray-500">Total Clicks</p>
                <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mt-0.5 sm:mt-1">{{ $links->sum('click_count') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6 hover:border-indigo-300 transition-colors xs:col-span-2 md:col-span-1">
        <div class="flex items-center gap-3 sm:gap-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-purple-50 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs sm:text-sm text-gray-500">Most Clicked</p>
                <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mt-0.5 sm:mt-1">{{ $links->max('click_count') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- LINKS TABLE --}}
<div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden">
    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Your Links</h3>
                <p class="text-gray-500 text-xs sm:text-sm">{{ count($links) }} total links</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 font-medium">
                    Export
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto -mx-4 sm:mx-0">
        <table class="w-full min-w-[768px] sm:min-w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-xs sm:text-sm font-semibold text-gray-700">Original URL</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-xs sm:text-sm font-semibold text-gray-700">Short URL</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-xs sm:text-sm font-semibold text-gray-700">Clicks</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 text-left text-xs sm:text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                @forelse($links as $link)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="py-4 sm:py-5 px-4 sm:px-6">
                        <div class="flex items-start gap-3 sm:gap-4 max-w-[280px] sm:max-w-lg">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 sm:mt-1">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate" title="{{ $link->original_url }}">
                                    {{ Str::limit($link->original_url, 40) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5 sm:mt-1 truncate">
                                    {{ parse_url($link->original_url, PHP_URL_HOST) }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 sm:py-5 px-4 sm:px-6">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <a href="{{ url('/s/'.$link->short_code) }}"
                               target="_blank"
                               class="text-indigo-600 hover:text-indigo-800 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-1.5 group break-all">
                                <span class="truncate max-w-[120px] sm:max-w-none">
                                    {{ url('/s/'.$link->short_code) }}
                                </span>
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-indigo-400 group-hover:text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                            <button onclick="copyLink('{{ url('/s/'.$link->short_code) }}')"
                                    class="text-gray-400 hover:text-gray-600 p-1 sm:p-1.5 hover:bg-gray-100 rounded-md transition-colors flex-shrink-0"
                                    title="Copy">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>

                    <td class="py-4 sm:py-5 px-4 sm:px-6">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ $link->click_count }}</span>
                            <span class="text-xs text-gray-500 hidden xs:inline">clicks</span>
                            @if($link->click_count > 0)
                            <div class="w-12 sm:w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                                <div class="h-full bg-indigo-500 rounded-full" 
                                     style="width: {{ min(($link->click_count / max(1, $links->max('click_count'))) * 100, 100) }}%"></div>
                            </div>
                            @endif
                        </div>
                    </td>

                    <td class="py-4 sm:py-5 px-4 sm:px-6">
                        <div class="flex items-center gap-1 sm:gap-1">
                            <button onclick="openEditModal('{{ $link->_id }}', '{{ $link->original_url }}')"
                                    class="p-1.5 sm:p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                    title="Edit">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>

                            <form method="POST" action="{{ url('/short-links/'.$link->_id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this link?')"
                                        class="p-1.5 sm:p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Delete">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 sm:py-12 px-4 sm:px-6">
                        <div class="flex flex-col items-center justify-center text-center max-w-sm mx-auto">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-xl sm:rounded-2xl flex items-center justify-center mb-3 sm:mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2">No links yet</h3>
                            <p class="text-gray-500 text-sm mb-3 sm:mb-4 px-4">
                                Create your first short link by entering a URL above
                            </p>
                            <button onclick="document.querySelector('input[name=\"original_url\"]').focus()"
                                    class="text-indigo-600 hover:text-indigo-700 font-medium text-xs sm:text-sm">
                                Create your first link →
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
  @if ($links->hasPages())
<div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs sm:text-sm">

        {{-- LEFT: Showing info --}}
        <div class="text-gray-600">
            Showing
            <span class="font-medium">{{ $links->firstItem() }}</span>
            to
            <span class="font-medium">{{ $links->lastItem() }}</span>
            of
            <span class="font-medium">{{ $links->total() }}</span>
            links
        </div>

        {{-- RIGHT: Pagination --}}
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            <a href="{{ $links->previousPageUrl() }}"
               class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 flex items-center justify-center rounded-lg
               {{ $links->onFirstPage() ? 'text-gray-300 pointer-events-none' : 'text-gray-600 hover:bg-gray-100' }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            {{-- Page numbers --}}
            @foreach ($links->getUrlRange(1, $links->lastPage()) as $page => $url)
                @if ($page == $links->currentPage())
                    <span
                        class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 flex items-center justify-center
                               bg-indigo-600 text-white rounded-lg font-medium">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 flex items-center justify-center
                              text-gray-600 hover:bg-gray-100 rounded-lg">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            <a href="{{ $links->nextPageUrl() }}"
               class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 flex items-center justify-center rounded-lg
               {{ $links->hasMorePages() ? 'text-gray-600 hover:bg-gray-100' : 'text-gray-300 pointer-events-none' }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
</div>
@endif

</div>

{{-- EDIT MODAL --}}
<div id="editModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-3 sm:p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl w-full max-w-md shadow-xl mx-auto max-h-[90vh] overflow-y-auto"
         id="modalContent">
        <div class="p-5 sm:p-6 md:p-8">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <div class="min-w-0">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">Edit URL</h3>
                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Update destination URL</p>
                </div>
                <button onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600 p-1.5 sm:p-2 hover:bg-gray-100 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-4 sm:space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Destination URL</label>
                    <input type="url"
                           name="original_url"
                           id="editUrl"
                           required
                           class="w-full border border-gray-300 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all duration-200 text-sm sm:text-base">
                </div>

                <div class="flex justify-end gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-gray-100">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 sm:px-5 py-2 sm:py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium text-sm sm:text-base">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-medium transition-colors text-sm sm:text-base">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS (UNCHANGED) --}}
<script>
function copyLink(text) {
    navigator.clipboard.writeText(text).then(() => {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 sm:top-6 right-3 sm:right-6 bg-gray-800 text-white px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl shadow-lg z-50 flex items-center gap-2 sm:gap-3 max-w-[90vw]';
        notification.innerHTML = `
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-xs sm:text-sm font-medium">Copied to clipboard</span>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 2000);
    });
}

function openEditModal(id, url) {
    const modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('editUrl').value = url;
    document.getElementById('editForm').action = `/short-links/${id}/update`;
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeEditModal();
});

document.getElementById('editModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'editModal') closeEditModal();
});
</script>

<style>
/* Balanced design system */
:root {
    --transition-base: 0.2s ease-in-out;
}

/* Smooth hover effects */
.bg-white {
    transition: box-shadow var(--transition-base), border-color var(--transition-base);
}

.bg-white:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Button interactions */
button {
    transition: all var(--transition-base);
}

/* Table row hover */
tbody tr {
    transition: background-color var(--transition-base);
}

/* Input focus states */
input:focus {
    transition: all var(--transition-base);
}

/* Link hover effects */
a {
    transition: color var(--transition-base);
}

/* Modal backdrop */
.bg-black\/40 {
    backdrop-filter: blur(2px);
}

/* Progress bar animation */
.bg-indigo-500 {
    transition: width 0.6s ease-in-out;
}

/* Responsive design */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }
    
    /* Ensure modal is scrollable on small screens */
    #editModal .max-h-\[90vh\] {
        max-height: 85vh;
    }
}

/* Extra small devices */
@media (max-width: 475px) {
    .xs\:grid-cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .xs\:col-span-2 {
        grid-column: span 2;
    }
    
    .xs\:inline {
        display: inline;
    }
    
    .xs\:hidden {
        display: none;
    }
}

/* Card shadows for depth */
.rounded-2xl {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* Prevent horizontal scroll on very small screens */
@media (max-width: 380px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Adjust spacing for very small screens */
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>

<script>
let searchTimer = null;

const input    = document.getElementById('searchInput');
const clearBtn = document.getElementById('clearSearchBtn');

input.addEventListener('input', function () {
    clearTimeout(searchTimer);

    // ❌ icon show / hide
    clearBtn.classList.toggle('hidden', !this.value);

    searchTimer = setTimeout(() => {
        fetchLinks(this.value);
    }, 300);
});

clearBtn.addEventListener('click', function () {
    input.value = '';
    clearBtn.classList.add('hidden');

    fetchLinks(''); // 🔥 load ALL links
});

function fetchLinks(query) {
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

        // 🔁 replace ONLY table + pagination
        document.querySelector('table').innerHTML =
            doc.querySelector('table').innerHTML;

        const pagination = document.querySelector('[class*="border-t"]');
        const newPagination = doc.querySelector('[class*="border-t"]');
        if (pagination && newPagination) {
            pagination.innerHTML = newPagination.innerHTML;
        }

        // URL update without reload
        history.replaceState(null, '', url);
    });
}
</script>

@endsection