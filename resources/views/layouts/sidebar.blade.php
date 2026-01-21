<script>
    window.isProUser = false
</script>

<div class="flex flex-col h-full">

  <!-- HEADER -->
  <div class="pb-4">
  <div class="flex items-center justify-between px-4 py-4 md:py-5">
      <div class="flex items-center gap-3">
        <!-- <div class="w-10 h-10 bg-gradient-to-br from-[#4C6FFF] to-[#8B5CF6] rounded-xl flex items-center justify-center font-bold text-xl text-white shadow-lg">
          
        </div> -->
        <div class="flex items-center gap-2">
                 <img
    src="{{ asset('assets/images/tracklio.png') }}"
    alt="Tracklio Logo"
    class="h-9 w-9 object-contain"
    loading="lazy"
/>

                    <span class="text-2xl font-bold text-white-600">
                        Tracklio
                    </span>
                </div>
       
      </div>

      <!-- Close button -->
      <button onclick="closeSidebar()"
              class="md:hidden p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors"
              aria-label="Close sidebar">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Navigation -->
<nav class="px-2 mt-2 space-y-1 flex-1 overflow-y-auto">
      <div class="px-3 pb-2">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</p>
      </div>

      <a href="{{ route('dashboard') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('/') ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="font-medium">Dashboard</span>
      </a>

      <a href="{{ route('posts.create') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('create-post') ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="font-medium">Create Post</span>
      </a>

      <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Calendar</span>
      </a>

      <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span class="font-medium">Analytics</span>
      </a>

      <a href="{{ route('accounts') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('accounts') ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="font-medium">Accounts</span>
      </a>

      <div class="px-3 pt-4 pb-2">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tools</p>
      </div>

      <a href="{{ route('short-links.index') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->routeIs('short-links.*') 
                   ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' 
                   : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
        <span class="font-medium">Short Links</span>
      </a>

      <a href="{{ route('qr-links.index') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('qr-links*') ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
        <span class="font-medium">QR & Links</span>
      </a>
<a href="{{ route('qr.builder') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('dynamic-qr*')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <!-- ICON -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 7h4v4H3V7zm14 0h4v4h-4V7zM3 17h4v4H3v-4zm10-6h4v4h-4v-4z"/>
    </svg>

    <span class="font-medium">Dynamic QR</span>
</a>

      <a href="{{ route('statistics.index') }}"
         class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('statistics') ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        <span class="font-medium">Statistics</span>
      </a>

      <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span class="font-medium">Settings</span>
      </a>
      <a href="{{ url('/contacts-page') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('contacts-page')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7
                 m10 0v-2c0-.656-.126-1.283-.356-1.857
                 M7 20H2v-2a3 3 0 015.356-1.857
                 M7 20v-2c0-.656.126-1.283.356-1.857
                 m0 0a5.002 5.002 0 019.288 0
                 M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>

    <span class="font-medium">Contacts</span>
</a>
<a href="{{ url('/whatsapp-broadcasts') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('whatsapp-broadcasts*')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <!-- Broadcast Icon -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 12a8 8 0 0116 0M6 12a6 6 0 0112 0M8 12a4 4 0 018 0M12 16v4"/>
    </svg>

    <span class="font-medium">WhatsApp Broadcast</span>
</a>


<a href="{{ url('/whatsapp-accounts') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('whatsapp-accounts')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 12a4 4 0 01-8 0 4 4 0 018 0zM12 14v7m-7-7h14"/>
    </svg>

    <span class="font-medium">WhatsApp Accounts</span>
</a>
<a href="{{ url('/whatsapp-campaigns') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('whatsapp-campaigns*')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <!-- ICON -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M21 16V8
                 a2 2 0 00-2-2H5a2 2 0 00-2 2v8
                 a2 2 0 002 2h14l4 4z"/>
    </svg>

    <span class="font-medium">WhatsApp Campaigns</span>
</a>

<a href="{{ route('bio.index') }}"
   class="pro-feature flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
          {{ request()->is('bio')
             ? 'bg-gradient-to-r from-[#4C6FFF] to-[#8B5CF6] text-white shadow-lg shadow-blue-500/25'
             : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

    <!-- ICON -->
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg>

    <span class="font-medium">Bio</span>
</a>

    </nav>
  </div>
 

  <!-- SIGN OUT -->
  <div class="border-t border-gray-800 pt-4 px-4 pb-6">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" 
              class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200 group">
        <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        <span class="font-medium">Sign Out</span>
      </button>
    </form>
  </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (!window.isProUser) {
        document.querySelectorAll('.pro-feature').forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault()
                showPlanModal()
            })
            el.classList.add('opacity-60', 'cursor-not-allowed', 'relative')
        })
    }
})
</script>
