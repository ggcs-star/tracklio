@extends('layouts.index')

@section('title', 'Accounts')

@section('content')

<div class="px-6 py-6 max-w-5xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="mb-10">
        <h2 class="text-2xl font-bold text-gray-900">Connected Accounts</h2>
        <p class="text-gray-500 text-sm mt-1">
            Manage your social media integrations.
        </p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5">
            {{ session('error') }}
        </div>
    @endif

    @php
        $platforms = [

            'facebook' => [
                'color' => '',
                'label' => 'Facebook',
                'icon' => '
                    <svg class="w-7 h-7" fill="#1877F2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89c1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                    </svg>
                '
            ],

            'instagram' => [
                'color' => '',
                'label' => 'Instagram',
                'icon' => '
                    <svg class="w-7 h-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="instaGradMain" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#fccc63"/>
                                <stop offset="25%" stop-color="#fca45a"/>
                                <stop offset="50%" stop-color="#e2346e"/>
                                <stop offset="75%" stop-color="#ac2b9e"/>
                                <stop offset="100%" stop-color="#4c2c92"/>
                            </linearGradient>
                        </defs>
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.309.975.975 1.247 2.242 1.309 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.309 3.608-.975.975-2.242 1.247-3.608 1.309-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.309-.975-.975-1.247-2.242-1.309-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.334-2.633 1.309-3.608.975-.975 2.242-1.247 3.608-1.309 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.332.014 7.052.072c-1.95.089-3.663.567-5.038 1.942C1.638 3.389 1.16 5.102 1.071 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.089 1.95.567 3.663 1.942 5.038 1.375 1.375 3.088 1.853 5.038 1.942 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c1.95-.089 3.663-.567 5.038-1.942 1.375-1.375 1.853-3.088 1.942-5.038.058-1.28.072-1.689.072-4.948s-.014-3.668-.072-4.948c-.089-1.95-.567-3.663-1.942-5.038C20.611 1.638 18.898 1.16 16.948 1.071 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z" fill="url(#instaGradMain)"/>
                    </svg>
                '
            ],

            'youtube' => [
                'color' => '',
                'label' => 'YouTube',
                'icon' => '
                    <svg class="w-7 h-7" fill="#FF0000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                    </svg>
                '
            ],

        ];

        $facebookAccount = $accounts->where('platform', 'facebook')->first();
        $facebookPages = [];
        if ($facebookAccount && isset($facebookAccount->pages) && is_array($facebookAccount->pages)) {
            $facebookPages = $facebookAccount->pages;
        }
        $youtubeAccounts = $accounts->where('platform', 'youtube');
        $instagramAccounts = $accounts->where('platform', 'instagram');
    @endphp

    <div class="space-y-6">

        {{-- FACEBOOK SECTION --}}
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-5 border-b cursor-pointer"
                onclick="toggleAccounts('facebookAccounts','facebookArrow')">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-transparent shadow-none">
                        {!! $platforms['facebook']['icon'] !!}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">Facebook</p>
                        <p class="text-sm text-gray-500">
                            {{ count($facebookPages) }} page(s) connected
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                     <button
                            type="button"
                            
                            class="text-gray-500"
                        >
                            <svg
                                id="facebookArrow"
                                class="w-5 h-5 transition-transform"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>
                    @if(count($facebookPages) > 0)
                        <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Connected
                        </span>
                        <button type="button" onclick="openFacebookModal()" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            + Add Account
                        </button>
                    @else
                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Disconnected</span>
                        <button onclick="openFacebookModal()" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            Connect
                        </button>
                    @endif
                </div>
            </div>
            @if(count($facebookPages) > 0)
                <div id="facebookAccounts" class="hidden divide-y divide-gray-100">
                    @foreach($facebookPages as $page)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-base">
                                    {{ strtoupper(substr($page['page_name'] ?? $page['profile_name'] ?? 'F', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $page['page_name'] ?? $page['profile_name'] ?? 'Facebook Page' }}</p>
                                    <p class="text-xs text-gray-500">Page connected</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('accounts.disconnect') }}" class="inline">
                                @csrf
                                <input type="hidden" name="platform" value="facebook">
                                <input type="hidden" name="account_id" value="{{ $facebookAccount->id ?? '' }}">
                                <input type="hidden" name="page_id" value="{{ $page['page_id'] ?? '' }}">
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Disconnect this page">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" d="M6 7h12M10 11v6m4-6v6M8 7h8l-1 12H9L8 7z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- INSTAGRAM SECTION --}}
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-5 border-b cursor-pointer"
                onclick="toggleAccounts('instagramAccounts','instagramArrow')">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-transparent shadow-none">
                        {!! $platforms['instagram']['icon'] !!}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">Instagram</p>
                        <p class="text-sm text-gray-500">
                            @if($instagramAccounts->count() > 0)
                                {{ $instagramAccounts->count() }} account(s) connected
                            @else
                                No account connected
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        
                        class="text-gray-500"
                    >
                        <svg
                            id="instagramArrow"
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                    @if($instagramAccounts->count() > 0)
                        <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Connected
                        </span>
                        <button type="button" onclick="openInstagramModal()" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            + Add Account
                        </button>
                    @else
                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Disconnected</span>
                        <button onclick="openInstagramModal()" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            Connect
                        </button>
                    @endif
                </div>
            </div>
            @if($instagramAccounts->count() > 0)
                <div id="instagramAccounts" class="hidden divide-y divide-gray-100">
                    @foreach($instagramAccounts as $account)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex items-center justify-center">
                                    {!! $platforms['instagram']['icon'] !!}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $account->credentials['username'] ?? 'Instagram User' }}</p>
                                    <p class="text-xs text-gray-500">Account connected</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('accounts.disconnect') }}" class="inline">
                                @csrf
                                <input type="hidden" name="platform" value="instagram">
                                <input type="hidden" name="account_id" value="{{ $account->id }}">
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Disconnect this account">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" d="M6 7h12M10 11v6m4-6v6M8 7h8l-1 12H9L8 7z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- YOUTUBE SECTION --}}
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-5 border-b cursor-pointer"
                onclick="toggleAccounts('youtubeAccounts','youtubeArrow')"
            >
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-transparent shadow-none">
                        {!! $platforms['youtube']['icon'] !!}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">YouTube</p>
                        <p class="text-sm text-gray-500">
                            @if($youtubeAccounts->count() > 0)
                                {{ $youtubeAccounts->count() }} channel(s) connected
                            @else
                                No channel connected
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        type="button"
                        
                        class="text-gray-500"
                    >
                        <svg
                            id="youtubeArrow"
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                    @if($youtubeAccounts->count() > 0)
                        <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Connected
                        </span>
                        <button type="button" onclick="window.location.href='{{ route('youtube.connect') }}'" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            + Add Account
                        </button>
                    @else
                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">Disconnected</span>
                        <button onclick="openYoutubeModal()" class="px-4 py-1.5 text-white bg-blue-600 rounded-lg text-sm hover:bg-blue-700 transition">
                            Connect
                        </button>
                    @endif
                </div>
            </div>
            @if($youtubeAccounts->count() > 0)
                <div id="youtubeAccounts" class="hidden divide-y divide-gray-100">
                    @foreach($youtubeAccounts as $account)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white font-semibold text-base">
                                    <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $account->credentials['channel_name'] ?? 'YouTube Channel' }}</p>
                                    <p class="text-xs text-gray-500">Channel connected</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('accounts.disconnect') }}" class="inline">
                                @csrf
                                <input type="hidden" name="platform" value="youtube">
                                <input type="hidden" name="account_id" value="{{ $account->id }}">
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Disconnect this channel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" d="M6 7h12M10 11v6m4-6v6M8 7h8l-1 12H9L8 7z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- FACEBOOK MODAL POPUP --}}
<div id="facebookModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-[500px] max-w-[90%] overflow-hidden">
        <div class="p-4 border-b flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-transparent shadow-none">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89c1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                    </svg>
                </div>
                <h3 class="font-semibold">Add your social accounts</h3>
            </div>
            <button onclick="closeFacebookModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-4" id="facebookModalContent">
            <div class="text-center py-4">Loading...</div>
        </div>
    </div>
</div>

{{-- YOUTUBE MODAL POPUP --}}
<div id="youtubeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[520px] max-w-[92%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-transparent shadow-none">
                    <svg class="w-6 h-6" fill="#FF0000" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-[28px] font-semibold text-[#2B2D42] leading-none">YouTube</h2>
                    <label class="flex items-center gap-2 mt-2 cursor-pointer">
                        <input type="checkbox" id="subscribeCheckbox" class="w-4 h-4 rounded border-gray-300 text-blue-500">
                        <span class="text-[15px] text-[#4F5D75]">Subscribe to our channal</span>
                    </label>
                </div>
            </div>
            <button onclick="closeYoutubeModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
        <div class="border-b mx-6"></div>
        <div class="px-8 py-8 flex justify-center">
            <button onclick="startYoutubeOAuth()" class="w-[130px] h-[130px] rounded-xl border bg-white hover:shadow-lg transition flex flex-col items-center justify-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3 bg-transparent shadow-none">
                    <svg class="w-8 h-8" fill="#FF0000" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                    </svg>
                </div>
                <span class="text-[20px] font-medium text-[#4F5D75]">YouTube</span>
            </button>
        </div>
    </div>
</div>

{{-- INSTAGRAM MODAL --}}
<div id="instagramModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[560px] max-w-[92%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full flex items-center justify-center bg-transparent shadow-none">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="instaGradModal" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#fccc63"/>
                                <stop offset="25%" stop-color="#fca45a"/>
                                <stop offset="50%" stop-color="#e2346e"/>
                                <stop offset="75%" stop-color="#ac2b9e"/>
                                <stop offset="100%" stop-color="#4c2c92"/>
                            </linearGradient>
                        </defs>
                        <path d="M7.75 2C4.574 2 2 4.574 2 7.75v8.5C2 19.426 4.574 22 7.75 22h8.5C19.426 22 22 19.426 22 16.25v-8.5C22 4.574 19.426 2 16.25 2h-8.5Zm0 2h8.5A3.75 3.75 0 0 1 20 7.75v8.5A3.75 3.75 0 0 1 16.25 20h-8.5A3.75 3.75 0 0 1 4 16.25v-8.5A3.75 3.75 0 0 1 7.75 4Zm8.75 1a1.25 1.25 0 1 0 0 2.5A1.25 1.25 0 0 0 16.5 5ZM12 7a5 5 0 1 0 0 10a5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6Z" fill="url(#instaGradModal)"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-[30px] font-semibold text-[#2B2D42] leading-none">Instagram</h2>
                    <p class="text-sm text-[#6B7280] mt-2">Connect your Instagram account</p>
                </div>
            </div>
            <button onclick="closeInstagramModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
        <div class="border-b mx-6"></div>
        <div class="px-8 py-8 flex items-center justify-center gap-5">
            <button onclick="openInstagramFacebookInfo()" class="w-[180px] h-[180px] rounded-2xl border bg-white hover:shadow-xl transition flex flex-col items-center justify-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4 bg-transparent shadow-none">
                    <svg class="w-10 h-10" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89c1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                    </svg>
                </div>
                <h3 class="text-[18px] font-semibold text-[#2B2D42]">Professional</h3>
                <p class="text-sm text-gray-500 mt-1">(via Facebook)</p>
            </button>
        </div>
    </div>
</div>

{{-- INSTAGRAM FACEBOOK INFO --}}
<div id="instagramFacebookInfo" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[500px] max-w-[92%] p-8 shadow-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-transparent shadow-none">
                <svg class="w-9 h-9" fill="#1877F2" viewBox="0 0 24 24">
                    <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89c1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                </svg>
            </div>
            <h2 class="text-[28px] font-semibold text-[#2B2D42]">Instagram with Facebook</h2>
        </div>

        <div class="space-y-4 text-[#4B5563] text-[14px]">
            <div>
                <div class="font-semibold text-[#2B2D42] mb-1">Pros:</div>
                <div class="ml-4 space-y-1">
                    <div>- You can tag collaborators and products</div>
                    <div>- Members can post using their own API credentials</div>
                </div>
            </div>

            <div>
                <div class="font-semibold text-[#2B2D42] mb-1">Cons:</div>
                <div class="ml-4 space-y-1">
                    <div>- Instagram needs to be connected to a Facebook Page</div>
                    <div>- Creator accounts cannot post stories automatically</div>
                </div>
            </div>

            <div>
                <div class="font-semibold text-[#2B2D42] mb-1">In both cases:</div>
                <div class="ml-4 space-y-1">
                    <div>- Meta API only supports Business and Creator accounts</div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <button onclick="closeInstagramFacebookInfo()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <button onclick="startInstagramFacebookOAuth()" class="px-7 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Continue</button>
        </div>
    </div>
</div>

{{-- YOUTUBE SELECT CHANNEL MODAL --}}
<div id="youtubeSelectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[560px] max-w-[92%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-transparent shadow-none">
                    <svg class="w-6 h-6" fill="#FF0000" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                    </svg>
                </div>
                <h2 class="text-[32px] font-semibold text-[#2B2D42]">YouTube</h2>
            </div>
            <button onclick="closeYoutubeSelectModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
        <div class="px-6 py-6">
            <p class="text-[15px] text-[#6B7280] mb-5">Select the accounts that you want to add.</p>
            @php
                $youtubeAccount = $accounts->firstWhere('id', session('youtube_connected_account_id'));
            @endphp
            @if($youtubeAccount)
                <div class="flex items-center justify-between bg-[#F8F9FB] rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center bg-transparent shadow-none">
                            <svg class="w-6 h-6" fill="#FF0000" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a2.974 2.974 0 0 0-2.092-2.104C19.555 3.5 12 3.5 12 3.5s-7.555 0-9.406.582A2.974 2.974 0 0 0 .502 6.186 31.13 31.13 0 0 0 0 12c0 1.943.168 3.86.502 5.814a2.974 2.974 0 0 0 2.092 2.104C4.445 20.5 12 20.5 12 20.5s7.555 0 9.406-.582a2.974 2.974 0 0 0 2.092-2.104C23.832 15.86 24 13.943 24 12c0-1.943-.168-3.86-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-[#2B2D42]">{{ $youtubeAccount->credentials['channel_name'] ?? 'YouTube Channel' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Connected just now</p>
                        </div>
                    </div>
                    <button onclick="closeYoutubeSelectModal(); closeYoutubeModal();" class="text-blue-600 font-semibold hover:text-blue-700 transition">Connect</button>
                </div>
            @endif
            <p class="text-[13px] text-[#7B8190] mt-5 leading-5">If you need to add another YouTube account, simply log out or switch YouTube accounts first.</p>
            <div class="flex justify-end mt-7">
                <button onclick="closeYoutubeSelectModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-lg transition">Continue</button>
            </div>
        </div>
    </div>
</div>

{{-- FACEBOOK SELECT MODAL --}}
<div id="facebookSelectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[560px] max-w-[92%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-transparent shadow-none">
                    <svg class="w-6 h-6" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M22 12A10 10 0 1 0 10.438 21.89v-6.922H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89c1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.969h-2.33v6.922A10 10 0 0 0 22 12Z"/>
                    </svg>
                </div>
                <h2 class="text-[32px] font-semibold text-[#2B2D42]">Facebook</h2>
            </div>
            <button onclick="closeFacebookSelectModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
        <div class="px-6 py-6">
            <p class="text-[15px] text-[#6B7280] mb-5">Select the pages that you want to add.</p>
            @php
                $facebookAccount = $accounts->where('platform', 'facebook')->first();
                $pages = $facebookAccount->pages ?? [];
            @endphp
            @if(!empty($pages))
                @foreach($pages as $page)
                <div class="flex items-center justify-between bg-[#F8F9FB] rounded-xl px-4 py-3 mb-3 hover:bg-[#F0F2F5] transition">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-lg">
                            {{ strtoupper(substr($page['page_name'] ?? $page['profile_name'] ?? 'F', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-[#2B2D42]">{{ $page['page_name'] ?? $page['profile_name'] ?? 'Facebook Page' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Connected just now</p>
                        </div>
                    </div>
                    <span class="text-green-600 font-semibold">Connected</span>
                </div>
                @endforeach
            @endif
            <div class="flex justify-end mt-7">
                <button onclick="closeFacebookSelectModal();" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-lg transition">Continue</button>
            </div>
        </div>
    </div>
</div>

{{-- INSTAGRAM SELECT MODAL --}}
<div id="instagramSelectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl w-[560px] max-w-[92%] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-transparent shadow-none">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="instaGradSelect" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#fccc63"/>
                                <stop offset="25%" stop-color="#fca45a"/>
                                <stop offset="50%" stop-color="#e2346e"/>
                                <stop offset="75%" stop-color="#ac2b9e"/>
                                <stop offset="100%" stop-color="#4c2c92"/>
                            </linearGradient>
                        </defs>
                        <path d="M7.75 2C4.574 2 2 4.574 2 7.75v8.5C2 19.426 4.574 22 7.75 22h8.5C19.426 22 22 19.426 22 16.25v-8.5C22 4.574 19.426 2 16.25 2h-8.5Zm0 2h8.5A3.75 3.75 0 0 1 20 7.75v8.5A3.75 3.75 0 0 1 16.25 20h-8.5A3.75 3.75 0 0 1 4 16.25v-8.5A3.75 3.75 0 0 1 7.75 4Zm8.75 1a1.25 1.25 0 1 0 0 2.5A1.25 1.25 0 0 0 16.5 5ZM12 7a5 5 0 1 0 0 10a5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6Z" fill="url(#instaGradSelect)"/>
                    </svg>
                </div>
                <h2 class="text-[32px] font-semibold text-[#2B2D42]">Instagram</h2>
            </div>
            <button onclick="closeInstagramSelectModal()" class="text-gray-400 hover:text-gray-600 text-3xl leading-none">&times;</button>
        </div>
        <div class="px-6 py-6">
            <p class="text-[15px] text-[#6B7280] mb-5">Select the accounts that you want to add.</p>
           @php
                $instagramAccount = $accounts->firstWhere(
                    'id',
                    session('instagram_connected_account_id')
                );
            @endphp
            @if($instagramAccount)
                <div class="flex items-center justify-between bg-[#F8F9FB] rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center bg-transparent shadow-none">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="instaGradUser" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#fccc63"/>
                                        <stop offset="25%" stop-color="#fca45a"/>
                                        <stop offset="50%" stop-color="#e2346e"/>
                                        <stop offset="75%" stop-color="#ac2b9e"/>
                                        <stop offset="100%" stop-color="#4c2c92"/>
                                    </linearGradient>
                                </defs>
                                <path d="M7.75 2C4.574 2 2 4.574 2 7.75v8.5C2 19.426 4.574 22 7.75 22h8.5C19.426 22 22 19.426 22 16.25v-8.5C22 4.574 19.426 2 16.25 2h-8.5Zm0 2h8.5A3.75 3.75 0 0 1 20 7.75v8.5A3.75 3.75 0 0 1 16.25 20h-8.5A3.75 3.75 0 0 1 4 16.25v-8.5A3.75 3.75 0 0 1 7.75 4Zm8.75 1a1.25 1.25 0 1 0 0 2.5A1.25 1.25 0 0 0 16.5 5ZM12 7a5 5 0 1 0 0 10a5 5 0 0 0 0-10Zm0 2a3 3 0 1 1 0 6a3 3 0 0 1 0-6Z" fill="url(#instaGradUser)"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-[#2B2D42]">{{ $instagramAccount->credentials['username'] ?? 'Instagram User' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Connected just now</p>
                        </div>
                    </div>
                    <span class="text-green-600 font-semibold">Connected</span>
                </div>
            @endif
            <div class="flex justify-end mt-7">
                <button onclick="closeInstagramSelectModal(); closeInstagramModal();" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-lg transition">Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
function openFacebookModal() {
    const modal = document.getElementById('facebookModal');
    const content = document.getElementById('facebookModalContent');
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="text-center py-4">Loading...</div>';
    fetch('/facebook/connect')
        .then(res => res.text())
        .then(html => {
            content.innerHTML = html;
            attachOptionListeners();
        })
        .catch(err => {
            content.innerHTML = '<div class="text-center py-4 text-red-500">Error loading options</div>';
        });
}

function closeFacebookModal() {
    document.getElementById('facebookModal').classList.add('hidden');
}
function openFacebookSelectModal() {
    const modal = document.getElementById('facebookSelectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeFacebookSelectModal() {
    const modal = document.getElementById('facebookSelectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function attachOptionListeners() {
    document.querySelectorAll('.fb-option').forEach(option => {
        option.addEventListener('click', function() {
            const type = this.dataset.type;
            startFacebookOAuth(type);
        });
    });
}

function startFacebookOAuth(type) {
    window.location.href = '/facebook/oauth?type=' + type;
}

function openYoutubeModal() {
    const modal = document.getElementById('youtubeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeYoutubeModal() {
    const modal = document.getElementById('youtubeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function startYoutubeOAuth() {
    const subscribeChecked = document.getElementById('subscribeCheckbox').checked;
    console.log('Subscribe checked:', subscribeChecked);
    window.location.href = '/youtube/connect';
}

function openYoutubeSelectModal() {
    const modal = document.getElementById('youtubeSelectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeYoutubeSelectModal() {
    const modal = document.getElementById('youtubeSelectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openInstagramModal() {
    const modal = document.getElementById('instagramModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeInstagramModal() {
    const modal = document.getElementById('instagramModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openInstagramFacebookInfo() {
    document.getElementById('instagramFacebookInfo').classList.remove('hidden');
    document.getElementById('instagramFacebookInfo').classList.add('flex');
}

function closeInstagramFacebookInfo() {
    document.getElementById('instagramFacebookInfo').classList.add('hidden');
    document.getElementById('instagramFacebookInfo').classList.remove('flex');
}

function startInstagramDirectOAuth() {
    window.location.href = '/instagram/connect/direct';
}

function startInstagramFacebookOAuth() {
    window.location.href = '/instagram/connect/facebook';
}

function openInstagramSelectModal() {
    const modal = document.getElementById('instagramSelectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeInstagramSelectModal() {
    const modal = document.getElementById('instagramSelectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
function toggleAccounts(contentId, arrowId)
{
    const content = document.getElementById(contentId);
    const arrow = document.getElementById(arrowId);

    content.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

</script>

@if(session('youtube_connected_popup'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        openYoutubeSelectModal();
    }, 300);
});
</script>
@endif
@if(session()->pull('facebook_connected_popup'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        openFacebookSelectModal();
    }, 300);
});
</script>
@endif
@if(session('instagram_connected_popup'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        openInstagramSelectModal();
    }, 300);
});
</script>
@endif

@endsection