@extends('layouts.index')

@section('title', 'WhatsApp Accounts')

@section('content')

{{-- 🔒 CSRF META (layout pe depend nahi) --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div
    x-data="whatsappAccounts()"
    x-init="fetchAccounts()"
    class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6"
>

    <!-- ================= MAIN CONTAINER ================= -->
    <div class="max-w-7xl mx-auto">
        
        <!-- ================= HEADER ================= -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900">WhatsApp Connections</h1>
                    </div>
                    <p class="text-gray-600">Manage your WhatsApp Business API connections and providers</p>
                </div>

                <button
                    type="button"
                    @click="openAdd = true"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 font-medium group"
                >
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Connect WhatsApp
                </button>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white rounded-xl p-4 shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Total Connections</p>
                    <p class="text-2xl font-bold" x-text="accounts.length"></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-2xl font-bold" x-text="accounts.length"></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Providers</p>
                    <p class="text-2xl font-bold">3</p>
                </div>
            </div>
        </div>

        <!-- ================= ACCOUNTS LIST ================= -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-semibold text-gray-800">Connected Accounts</h2>
                <p class="text-sm text-gray-600 mt-1">All your WhatsApp Business API connections</p>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Provider</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Business ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="a in accounts" :key="a._id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                                <span class="font-bold text-green-700" x-text="a.provider.charAt(0).toUpperCase()"></span>
                                            </div>
                                            <span class="font-medium text-gray-900" x-text="a.provider.toUpperCase()"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span class="font-mono text-sm text-gray-700" x-text="a.phone_number_id"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            <span class="font-mono text-sm text-gray-700 break-all" x-text="a.business_account_id"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button
                                            type="button"
                                            @click="removeAccount(a._id)"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            
                            <tr x-show="accounts.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="max-w-md mx-auto">
                                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No WhatsApp Connections</h3>
                                        <p class="text-gray-500 mb-6">Connect your first WhatsApp Business API account to get started</p>
                                        <button
                                            @click="openAdd = true"
                                            class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-shadow"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Connect First Account
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
                    <template x-for="a in accounts" :key="a._id">
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                        <span class="font-bold text-lg text-green-700" x-text="a.provider.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900" x-text="a.provider.toUpperCase()"></h3>
                                        <span class="inline-flex items-center mt-1 px-2 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            Active
                                        </span>
                                    </div>
                                </div>
                                <button
                                    @click="removeAccount(a._id)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-3 pl-1">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Phone ID</p>
                                    <p class="font-mono text-sm text-gray-700 break-all" x-text="a.phone_number_id"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Business ID</p>
                                    <p class="font-mono text-sm text-gray-700 break-all" x-text="a.business_account_id"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="accounts.length === 0" class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Connections</h3>
                        <button
                            @click="openAdd = true"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-2.5 rounded-lg mt-4"
                        >
                            Connect Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= ADD ACCOUNT MODAL ================= -->
    <div
        x-show="openAdd"
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
            x-show="openAdd"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="openAdd = false"
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
                            <h2 class="text-xl font-bold text-white">Connect WhatsApp</h2>
                            <p class="text-sm text-green-100">Add new WhatsApp Business API</p>
                        </div>
                    </div>
                    <button @click="openAdd = false" class="text-white hover:text-green-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Provider Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="provider in ['meta', 'twilio', 'gupshup']" :key="provider">
                                <button
                                    type="button"
                                    @click="form.provider = provider"
                                    :class="form.provider === provider 
                                        ? 'border-green-500 bg-green-50 text-green-700' 
                                        : 'border-gray-200 hover:border-gray-300 text-gray-600 hover:text-gray-900'"
                                    class="p-3 border rounded-lg text-center transition-colors"
                                >
                                    <span class="font-medium uppercase" x-text="provider"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number ID</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    placeholder="Enter Phone Number ID"
                                    x-model="form.phone_number_id"
                                    class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business Account ID</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    placeholder="Enter Business Account ID"
                                    x-model="form.business_account_id"
                                    class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Access Token</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    placeholder="Enter Access Token"
                                    x-model="form.access_token"
                                    class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button
                        type="button"
                        @click="openAdd = false"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click.prevent="saveAccount()"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-shadow font-medium"
                    >
                        Connect Account
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ================= ALPINE SCRIPT ================= -->
<script>
function whatsappAccounts() {
    return {
        accounts: [],
        openAdd: false,
        form: {
            provider: 'meta',
            phone_number_id: '',
            business_account_id: '',
            access_token: ''
        },

        fetchAccounts() {
            fetch('/whatsapp-accounts/data')
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        this.accounts = res.data;
                    }
                });
        },

        saveAccount() {
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/whatsapp-accounts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    alert(res.message || 'Failed to connect WhatsApp');
                    return;
                }

                this.accounts.unshift(res.data);
                this.openAdd = false;
                this.form = {
                    provider: 'meta',
                    phone_number_id: '',
                    business_account_id: '',
                    access_token: ''
                };
            })
            .catch(err => {
                console.error(err);
                alert('JS Error – check console');
            });
        },

        removeAccount(id) {
            if (!confirm('Are you sure you want to disconnect this WhatsApp account? This action cannot be undone.')) return;

            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/whatsapp-accounts/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    this.accounts = this.accounts.filter(a => a._id !== id);
                }
            });
        }
    }
}
</script>

@endsection