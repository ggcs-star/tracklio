<header class="w-full bg-white border-b flex items-center justify-between 
               px-4 sm:px-6 md:px-10 h-auto py-3 flex-wrap gap-3">

    {{-- PAGE TITLE --}}
    <h2 class="text-xl font-bold whitespace-nowrap order-1">
        Dashboard
    </h2>

    {{-- RIGHT SECTION --}}
    <div class="flex items-center space-x-6 order-2 sm:order-3 ml-auto">

        {{-- NOTIFICATION --}}
        <div class="relative cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-gray-600"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405C18.79 14.79 18 13.42 
                   18 12V8a6 6 0 10-12 0v4c0 1.42-.79 2.79-1.595 
                   3.595L3 17h5m7 0v1a3 3 0 11-6 0v-1"/>
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
        </div>

        {{-- USER SECTION --}}
        @php
            $user = Auth::user();
            $name = $user ? $user->name : 'Guest User';
            $role = $user ? 'Admin' : 'User';
            $initials = $user ? strtoupper(substr($user->name, 0, 2)) : 'GU';
        @endphp

        <div class="flex items-center space-x-3">

            {{-- USER NAME --}}
            <div class="text-right leading-tight hidden sm:block">
                <p class="font-semibold text-gray-800 text-sm">
                    {{ $name }}
                </p>
                <p class="text-xs text-gray-400 -mt-1">
                    {{ $role }}
                </p>
            </div>

@auth
<a href="{{ route('profile') }}"
   class="flex items-center space-x-3 relative z-50">

    {{-- USER NAME --}}
    <div class="text-right leading-tight hidden sm:block">
        <p class="font-semibold text-gray-800 text-sm">
            {{ $name }}
        </p>
        <p class="text-xs text-gray-400 -mt-1">
            {{ $role }}
        </p>
    </div>

    {{-- AVATAR --}}
    @if(auth()->user()->avatar)
        <img src="{{ asset('storage/'.auth()->user()->avatar) }}"
             class="w-10 h-10 rounded-full object-cover cursor-pointer">
    @else
        <div class="w-10 h-10 bg-[#EEF1FF] rounded-full flex items-center 
                    justify-center text-[#4C6FFF] font-bold cursor-pointer">
            {{ $initials }}
        </div>
    @endif
</a>
@endauth
</div>
</div>

    {{-- SEARCH BAR --}}
    <div class="flex items-center bg-[#F4F6FA] px-4 h-[42px] rounded-full border
                w-full sm:w-[260px] md:w-[300px] lg:w-[350px]
                order-3 sm:order-2 mx-auto sm:mx-0">

        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
        </svg>

        <input type="text"
               placeholder="Search posts…"
               class="bg-transparent outline-none text-gray-600 w-full text-sm" />
    </div>

</header>
