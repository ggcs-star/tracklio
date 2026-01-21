@extends('layouts.index')

@section('title','WhatsApp Campaigns')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Alpine --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div
    x-data="campaignApp()"
    x-init="init()"
    class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 p-4 md:p-6"
>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Campaign Manager</h1>
                            <p class="text-gray-600">Create and send WhatsApp campaigns to your contacts</p>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    @click="openCreate = true"
                    class="group inline-flex items-center gap-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 font-semibold"
                >
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Campaign
                </button>
            </div>
<!-- STATS CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

    <!-- Sent Campaigns -->
    <div class="bg-white rounded-xl p-4 shadow border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Sent Campaigns</p>
        <p class="text-2xl font-bold"
           x-text="(campaigns || []).filter(c => c.status === 'sent').length">
        </p>
    </div>

    <!-- Total Campaigns -->
    <div class="bg-white rounded-xl p-4 shadow border-l-4 border-indigo-500">
        <p class="text-sm text-gray-500">Total Campaigns</p>
        <p class="text-2xl font-bold"
           x-text="(campaigns || []).length">
        </p>
    </div>

    <!-- Active Campaigns -->
    <div class="bg-white rounded-xl p-4 shadow border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Active Campaigns</p>
        <p class="text-2xl font-bold"
           x-text="(campaigns || []).filter(c => c.status !== 'sent').length">
        </p>
    </div>

    <!-- Groups -->
    <div class="bg-white rounded-xl p-4 shadow border-l-4 border-purple-500">
        <p class="text-sm text-gray-500">Groups</p>
        <p class="text-2xl font-bold"
           x-text="(groups || []).length">
        </p>
    </div>

</div>
    


        <!-- CAMPAIGNS LIST -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-semibold text-gray-800">Your Campaigns</h2>
                <p class="text-sm text-gray-600 mt-1">Manage and send your WhatsApp campaigns</p>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="c in campaigns" :key="c._id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900" x-text="c.name"></h3>
                                                <p class="text-xs text-gray-500">Created recently</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium"
                                            :class="c.status === 'sent'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800'"
                                        >
                                            <span 
                                                class="w-2 h-2 rounded-full mr-2"
                                                :class="c.status === 'sent' ? 'bg-green-500' : 'bg-gray-400'"
                                            ></span>
                                            <span x-text="c.status.charAt(0).toUpperCase() + c.status.slice(1)"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
     <button
    type="button"
    @click="c.status !== 'sent' && openSendModal(c)"
    :disabled="c.status === 'sent'"
    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition
           bg-gradient-to-r from-indigo-500 to-purple-600 text-white hover:shadow-lg
           disabled:opacity-50 disabled:cursor-not-allowed"
>

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
Send Message
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            
                            <tr x-show="campaigns.length === 0">
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Campaigns Yet</h3>
                                        <p class="text-gray-500 mb-6">Create your first WhatsApp campaign to get started</p>
                                        <button
                                            @click="openCreate = true"
                                            class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-shadow"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Create First Campaign
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
<!-- Mobile Cards -->
<div class="lg:hidden">
    <div class="divide-y divide-gray-200">
        <template x-for="c in campaigns" :key="c._id">
            <div class="p-4 hover:bg-gray-50 transition-colors">
                
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15
                                         M18 13a3 3 0 100-6
                                         M5.436 13.683A4.001 4.001 0 017 6
                                         h1.832c4.1 0 7.625-1.234 9.168-3v14
                                         c-1.543-1.766-5.067-3-9.168-3H7
                                         a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>

                        <!-- Name + Status -->
                        <div>
                            <h3 class="font-semibold text-gray-900" x-text="c.name"></h3>

                            <span
                                class="inline-flex items-center mt-1 px-2 py-1 rounded-full text-xs font-medium"
                                :class="c.status === 'sent'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-gray-100 text-gray-700'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full mr-1"
                                    :class="c.status === 'sent' ? 'bg-green-500' : 'bg-gray-400'"
                                ></span>
                                <span x-text="c.status.charAt(0).toUpperCase() + c.status.slice(1)"></span>
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Action -->
           <button
    type="button"
    @click="c.status !== 'sent' && openSendModal(c)"
    :disabled="c.status === 'sent'"
    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition
           bg-gradient-to-r from-indigo-500 to-purple-600 text-white hover:shadow-lg
           disabled:opacity-50 disabled:cursor-not-allowed"
>

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send
                    </button>
                </div>

            </div>
        </template>

        <!-- Empty -->
        <div x-show="campaigns.length === 0" class="p-8 text-center">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Campaigns</h3>
            <button
                @click="openCreate = true"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600
                       text-white px-5 py-2.5 rounded-lg mt-4"
            >
                Create Campaign
            </button>
        </div>
    </div>
</div>


    <!-- CREATE MODAL -->
    <div
        x-show="openCreate"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div
            x-show="openCreate"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="openCreate = false"
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">New Campaign</h2>
                            <p class="text-sm text-green-100">Create a new WhatsApp campaign</p>
                        </div>
                    </div>
                    <button @click="openCreate = false" class="text-white hover:text-green-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            x-model="newName"
                            placeholder="e.g., Summer Sale 2024"
                            class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                            @keyup.enter="createCampaign"
                        >
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button
                        type="button"
                        @click="openCreate = false"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="createCampaign()"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-shadow font-medium"
                    >
                        Create Campaign
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SEND MODAL -->
    <div
        x-show="openSend"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div
            x-show="openSend"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="openSend = false"
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
<h2 class="text-xl font-bold text-white">Send Message</h2>
                            <p class="text-sm text-indigo-100">
                                Campaign: <span class="font-semibold" x-text="activeCampaign?.name"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="openSend = false" class="text-white hover:text-indigo-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Contacts Selection -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
<h3 class="text-lg font-semibold text-gray-800">Select Recipients</h3>
<p class="text-sm text-gray-500">
    This campaign can be used multiple times
</p>
                        <div class="text-sm text-gray-600">
Selected:
<span class="font-bold" x-text="selectedGroup ? 1 : 0"></span> group
                        </div>
                    </div>
                    
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="max-h-80 overflow-y-auto">
<template x-for="g in groups" :key="g._id">
    <label
        class="flex items-center gap-4 px-4 py-3 border-b border-gray-200
               hover:bg-gray-50 transition-colors cursor-pointer"
    >
        <div class="flex items-center">
            <input
                type="radio"
                name="broadcast_group"
:value="g._id"
                x-model="selectedGroup"
                class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            >
        </div>

        <div class="flex-1">
            <div class="flex items-center justify-between">
                <span class="font-medium text-gray-900" x-text="g.name"></span>
                <span class="text-sm text-gray-600"
                      x-text="g.contacts_count + ' contacts'">
                </span>
            </div>

            <div class="flex items-center gap-2 mt-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                             c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                             a3 3 0 015.356-1.857M7 20v-2
                             c0-.656.126-1.283.356-1.857
                             m0 0a5.002 5.002 0 019.288 0
                             M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs text-gray-500">Broadcast Group</span>
            </div>
        </div>
    </label>
</template>

                        </div>
                    </div>
                </div>

                <!-- Template Info -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Message Template</p>
                            <div class="flex items-center gap-2">
                                <code class="px-3 py-1 bg-white rounded-lg text-sm font-mono text-indigo-700 border border-indigo-200">hello_world</code>
                                <span class="text-xs text-gray-500">(Default Template)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button
                        type="button"
                        @click="openSend = false"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
   <button
    type="button"
    @click="sendCampaign()"
    :disabled="!selectedGroup"
    class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600
           text-white rounded-lg hover:shadow-lg transition-shadow font-medium
           disabled:opacity-50 disabled:cursor-not-allowed"
>
    <span x-show="selectedGroup">Send Campaign</span>
    <span x-show="!selectedGroup">Select group to send</span>
</button>

                </div>
            </div>
        </div>
    </div>

</div>
<script>
function campaignApp() {
    return {
        campaigns: @json($campaigns),
        groups: @json($groups),

        openCreate: false,
        openSend: false,

        newName: '',
        activeCampaign: null,
        selectedGroup: null,

        csrf: '',

        init() {
            this.csrf = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');
        },

        createCampaign() {
            if (!this.newName.trim()) return;

            fetch('/whatsapp-campaigns', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf
                },
                body: JSON.stringify({
                    name: this.newName
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    this.campaigns.unshift(res.data);
                    this.newName = '';
                    this.openCreate = false;
                } else {
                    alert(res.message || 'Create failed');
                }
            })
            .catch(e => {
                console.error(e);
                alert('Create error');
            });
        },

        openSendModal(c) {
            this.activeCampaign = c;
            this.selectedGroup = null;
            this.openSend = true;
        },

      sendCampaign() {
    if (!this.selectedGroup || !this.activeCampaign) {
        alert('Select campaign & group');
        return;
    }

    fetch('/whatsapp-campaigns/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrf
        },
        body: JSON.stringify({
            campaign_id: this.activeCampaign._id, // ✅ COMMA ADDED
            group_id: this.selectedGroup,
            template: 'hello_world'
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            this.openSend = false;

            // optional UI update
            this.activeCampaign.status = 'sent';
        } else {
            alert(res.message || 'Send failed');
        }
    })
    .catch(e => {
        console.error(e);
        alert('Send error');
    });
}

    }
}
</script>

@endsection