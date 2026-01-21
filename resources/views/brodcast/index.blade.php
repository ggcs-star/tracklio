@extends('layouts.index')

@section('title', 'Broadcast Groups')

@section('content')

{{-- Alpine.js --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div
    x-data="broadcastManager()"
    x-init="init()"
    class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50/50"
>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8">
        
        <!-- HEADER SECTION -->
        <div class="mb-8 md:mb-12">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 shadow-xl">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>
                
                <div class="relative z-10 px-6 py-8 md:px-8 md:py-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="p-2 bg-white/20 backdrop-blur-sm rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-1">Broadcast Groups</h1>
                                    <p class="text-indigo-100 text-sm md:text-base">Create and manage WhatsApp broadcast audiences</p>
                                </div>
                            </div>
                        </div>
                        
                        <button
                            @click="openCreateModal()"
                            class="group inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm hover:bg-white/20 border border-white/30 text-white px-5 md:px-6 py-3 md:py-3.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 font-semibold"
                        >
                            <svg class="w-5 h-5 md:w-6 md:h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="text-sm md:text-base">Create Group</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- GROUPS GRID -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="px-4 md:px-6 py-4 md:py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg md:text-xl font-semibold text-gray-900">Your Broadcast Groups</h2>
                        <p class="text-sm text-gray-600 mt-1">Click on a group to view and manage contacts</p>
                    </div>
                    <div class="relative">
                        <input
                            type="text"
                            x-model="searchQuery"
                            @input.debounce.300ms="filterGroups()"
                            placeholder="Search groups..."
                            class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-48"
                        >
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <template x-if="filteredGroups.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <template x-for="group in filteredGroups" :key="group.id">
                            <div class="group relative bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-4 md:p-6 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-300 cursor-pointer">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="p-2 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg">
                                                <svg class="w-4 h-4 md:w-5 md:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <h3 class="font-semibold text-gray-900 text-lg" x-text="group.name"></h3>
                                        </div>
                                        <p class="text-sm text-gray-500" x-text="group.description || 'No description'"></p>
                                    </div>
                                    <button
                                        @click.stop="openDeleteDialog(group)"
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="text-gray-600">Contacts</span>
                                        </div>
                                        <span class="font-semibold text-gray-900" x-text="group.contacts_count"></span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-gray-600">Created</span>
                                        </div>
                                        <span class="text-gray-500" x-text="formatDate(group.created_at)"></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-100">
                                    <button
                                        @click.stop="openSendModal(group)"
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:shadow-md transition-shadow"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Send
                                    </button>
                                    <button
                                        @click.stop="openEditModal(group)"
                                        class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="filteredGroups.length === 0">
                    <div class="py-12 md:py-16 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg md:text-xl font-semibold text-gray-700 mb-2">
                                <template x-if="searchQuery">No groups found</template>
                                <template x-if="!searchQuery">No broadcast groups yet</template>
                            </h3>
                            <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                                <template x-if="searchQuery">Try a different search term</template>
                                <template x-if="!searchQuery">Create your first broadcast group to start sending messages</template>
                            </p>
                            <button
                                @click="openCreateModal()"
                                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-5 py-2.5 rounded-lg hover:shadow-lg transition-shadow"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <template x-if="searchQuery">Clear Search</template>
                                <template x-if="!searchQuery">Create First Group</template>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- CREATE / EDIT GROUP MODAL -->
    <div
        x-show="openModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div
            x-show="openModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="openModal = false"
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
        >
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white" x-text="editingGroup ? 'Edit Group' : 'Create New Group'"></h2>
                            <p class="text-sm text-indigo-100">
                                <template x-if="editingGroup">Update your broadcast group</template>
                                <template x-if="!editingGroup">Add contacts to create a broadcast audience</template>
                            </p>
                        </div>
                    </div>
                    <button @click="openModal = false" class="text-white hover:text-indigo-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Group Name</label>
                        <input
                            type="text"
                            x-model="form.name"
                            placeholder="e.g., HR Team, VIP Customers"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea
                            x-model="form.description"
                            rows="2"
                            placeholder="Describe this group..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- CONTACT SELECTION SECTION -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Select Contacts</h3>
                            <p class="text-sm text-gray-500">Choose contacts to include in this broadcast group</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">
                                Selected: <span class="font-bold text-indigo-600" x-text="selectedContacts.length"></span>
                            </span>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input
                                    type="checkbox"
                                    @change="toggleAllVisibleContacts"
                                    :checked="visibleContacts.length > 0 && visibleContacts.every(c => selectedContacts.includes(c.id))"
                                    class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                >
                                Select All
                            </label>
                        </div>
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="relative mb-4">
                        <input
                            type="text"
                            x-model="contactSearch"
                            @input.debounce.300ms="filterContacts"
                            placeholder="Search contacts by name or phone..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-sm"
                        >
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <button
                            x-show="contactSearch"
                            @click="contactSearch = ''; filterContacts()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- VIRTUAL SCROLL CONTAINER -->
                    <div
                        x-ref="scrollContainer"
                        @scroll.debounce.50ms="handleScroll($event.target)"
                        class="border border-gray-300 rounded-lg overflow-hidden relative"
                        style="height: 256px;"
                    >
                        <!-- TOP SPACER -->
                        <div x-bind:style="'height: ' + topPadding + 'px'"></div>

                        <!-- VISIBLE CONTACTS -->
                        <template x-for="contact in visibleContacts" :key="contact.id">
                            <label class="flex items-center gap-4 px-4 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer last:border-b-0" style="height: 64px;">
                                <input
                                    type="checkbox"
                                    :value="contact.id"
                                    x-model="selectedContacts"
                                    class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                >
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                            <span class="text-sm font-semibold text-blue-600" x-text="contact.name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900 truncate" x-text="contact.name"></span>
                                                <span class="text-sm font-mono text-gray-600 ml-2" x-text="contact.phone"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </template>

                        <!-- BOTTOM SPACER -->
                        <div x-bind:style="'height: ' + bottomPadding + 'px'"></div>

                        <!-- EMPTY STATE -->
                        <template x-if="filteredContacts.length === 0">
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <template x-if="contactSearch">No contacts match your search</template>
                                    <template x-if="!contactSearch && contacts.length === 0">No contacts available</template>
                                    <template x-if="!contactSearch && contacts.length > 0">All contacts are hidden</template>
                                </p>
                            </div>
                        </template>
                    </div>

                    <!-- COUNTER INFO -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                        <span>
                            Showing <span class="font-medium" x-text="visibleContacts.length"></span> of 
                            <span class="font-medium" x-text="filteredContacts.length"></span> contacts
                            <template x-if="contactSearch">
                                (filtered from <span x-text="contacts.length"></span> total)
                            </template>
                        </span>
                        <span>
                            <span class="font-medium" x-text="selectedContacts.length"></span> selected
                        </span>
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button
                        type="button"
                        @click="openModal = false"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="saveGroup()"
                        :disabled="!form.name.trim() || selectedContacts.length === 0"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-shadow font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <template x-if="editingGroup">Update Group</template>
                        <template x-if="!editingGroup">Create Group</template>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div
        x-show="openDelete"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
        <div
            x-show="openDelete"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-red-200 bg-gradient-to-r from-red-50 to-rose-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Delete Group</h2>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete <span class="font-semibold" x-text="groupToDelete?.name"></span>?
                    This will remove the group and all its contact associations.
                </p>
                
                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="openDelete = false"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deleteGroup()"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-lg hover:shadow-lg transition-shadow font-medium"
                    >
                        Delete Group
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SEND MESSAGE MODAL -->
    <div
        x-show="openSend"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
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
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
        >
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Send Broadcast</h2>
                            <p class="text-sm text-green-100">Send message to group contacts</p>
                        </div>
                    </div>
                    <button @click="openSend = false" class="text-white hover:text-green-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        Send a message to <span class="font-semibold" x-text="groupToSend?.contacts_count"></span> 
                        contacts in <span class="font-semibold text-indigo-600" x-text="groupToSend?.name"></span>
                    </p>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message Template</label>
                    <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="hello_world">Hello World (Default)</option>
                        <option value="welcome">Welcome Message</option>
                        <option value="promotion">Promotional Offer</option>
                        <option value="notification">Notification Alert</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="openSend = false"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="sendBroadcast()"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-shadow font-medium"
                    >
                        Send Broadcast
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function broadcastManager() {
    return {
        /* =================== DATA =================== */
        groups: @json($groups ?? []),
        contacts: [],

        /* UI States */
        loading: false,
        searchQuery: '',
        openModal: false,
        openDelete: false,
        openSend: false,
        editingGroup: null,
        groupToDelete: null,
        groupToSend: null,

        /* Form */
        form: {
            name: '',
            description: '',
            contact_ids: []
        },

        /* Contact Selection */
        contactSearch: '',
        selectedContacts: [],
        scrollTop: 0,

        /* Virtual Scroll */
        rowHeight: 64,
        viewportHeight: 256,

        /* =================== COMPUTED =================== */
        get filteredGroups() {
            if (!this.searchQuery.trim()) return this.groups;
            const q = this.searchQuery.toLowerCase();
            return this.groups.filter(g =>
                g.name.toLowerCase().includes(q) ||
                (g.description && g.description.toLowerCase().includes(q))
            );
        },

        get filteredContacts() {
            if (!this.contactSearch.trim()) return this.contacts;
            const q = this.contactSearch.toLowerCase();
            return this.contacts.filter(c =>
                c.name.toLowerCase().includes(q) ||
                (c.phone && c.phone.toLowerCase().includes(q))
            );
        },

        get startIndex() {
            return Math.floor(this.scrollTop / this.rowHeight);
        },

        get endIndex() {
            return Math.min(
                this.startIndex + Math.ceil(this.viewportHeight / this.rowHeight),
                this.filteredContacts.length
            );
        },

        get visibleContacts() {
            return this.filteredContacts.slice(this.startIndex, this.endIndex);
        },

        get topPadding() {
            return this.startIndex * this.rowHeight;
        },

        get bottomPadding() {
            return Math.max(
                0,
                (this.filteredContacts.length - this.endIndex) * this.rowHeight
            );
        },

        /* =================== INIT =================== */
        async init() {
            this.csrf = document.querySelector('meta[name="csrf-token"]').content;
            await this.loadContacts();
            await this.loadGroups();
        },

        async loadContacts() {
            const res = await fetch('/broadcast-groups/contacts');
            const data = await res.json();
            if (data.success) this.contacts = data.data;
        },

        async loadGroups() {
            const res = await fetch('/broadcast-groups');
            const data = await res.json();
            if (data.success) this.groups = data.data;
        },

        /* =================== MODALS =================== */
        openCreateModal() {
            this.editingGroup = null;
            this.form = { name: '', description: '', contact_ids: [] };
            this.selectedContacts = [];
            this.contactSearch = '';
            this.scrollTop = 0;
            this.openModal = true;
        },

        openEditModal(group) {
            this.editingGroup = group;
            this.form = {
                name: group.name,
                description: group.description || '',
                contact_ids: group.contact_ids || []
            };
            this.selectedContacts = [...this.form.contact_ids];
            this.openModal = true;
        },

        openDeleteDialog(group) {
            this.groupToDelete = group;
            this.openDelete = true;
        },

        openSendModal(group) {
            this.groupToSend = group;
            this.openSend = true;
        },

        /* =================== CONTACT SELECT =================== */
        toggleAllVisibleContacts() {
            const ids = this.visibleContacts.map(c => c.id);
            const all = ids.every(id => this.selectedContacts.includes(id));
            this.selectedContacts = all
                ? this.selectedContacts.filter(id => !ids.includes(id))
                : [...new Set([...this.selectedContacts, ...ids])];
        },

        handleScroll(el) {
            this.scrollTop = el.scrollTop;
        },

        filterContacts() {
            this.scrollTop = 0;
        },

        /* =================== SAVE GROUP =================== */
        async saveGroup() {
            if (!this.form.name.trim() || !this.selectedContacts.length) return;

            const isEdit = !!this.editingGroup;
            const url = isEdit
                ? `/broadcast-groups/${this.editingGroup.id}`
                : '/broadcast-groups';

            const res = await fetch(url, {
                method: isEdit ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf
                },
                body: JSON.stringify({
                    name: this.form.name,
                    description: this.form.description,
                    contact_ids: this.selectedContacts
                })
            });

            const data = await res.json();
            if (!data.success) return;

            if (isEdit) {
                const i = this.groups.findIndex(g => g.id === this.editingGroup.id);
                if (i !== -1) this.groups[i] = data.data;
            } else {
                this.groups.unshift(data.data);
            }

            this.openModal = false;
            this.editingGroup = null;
            this.selectedContacts = [];
        },

        /* =================== DELETE GROUP =================== */
        async deleteGroup() {
            const res = await fetch(`/broadcast-groups/${this.groupToDelete.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': this.csrf }
            });

            const data = await res.json();
            if (data.success) {
                this.groups = this.groups.filter(g => g.id !== this.groupToDelete.id);
                this.openDelete = false;
            }
        },

        /* =================== SEND =================== */
        async sendBroadcast() {
            await fetch('/broadcast-groups/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf
                },
                body: JSON.stringify({
                    group_id: this.groupToSend.id,
                    template: 'hello_world'
                })
            });
            this.openSend = false;
        },

        /* =================== UTIL =================== */
        formatDate(d) {
            return new Date(d).toLocaleDateString();
        }
    };
}
</script>


<style>
/* Custom Scrollbar */
[x-cloak] {
    display: none !important;
}

/* Virtual Scroll Container Scrollbar */
[x-ref="scrollContainer"]::-webkit-scrollbar {
    width: 6px;
}

[x-ref="scrollContainer"]::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

[x-ref="scrollContainer"]::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #8b5cf6, #6366f1);
    border-radius: 3px;
}

[x-ref="scrollContainer"]::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #7c3aed, #4f46e5);
}

/* Focus Styles */
input:focus, button:focus, textarea:focus, select:focus {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
}

/* Smooth Transitions */
input, button, textarea, select, label {
    transition: all 0.2s ease;
}

/* Button Hover Effects */
button:hover {
    transform: translateY(-1px);
}

/* Card Hover Effects */
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Responsive Design */
@media (max-width: 640px) {
    .grid-cols-1 {
        grid-template-columns: 1fr !important;
    }
    
    .max-w-2xl {
        max-width: 95% !important;
    }
}

/* Touch-friendly sizes for mobile */
@media (max-width: 768px) {
    button, input, label {
        min-height: 44px;
    }
    
    input, textarea {
        font-size: 16px !important;
    }
}

/* Loading animation for empty states */
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

/* Custom gradient backgrounds */
.bg-gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

/* WhatsApp-like colors */
.bg-whatsapp-green {
    background-color: #25D366;
}

.text-whatsapp-green {
    color: #25D366;
}

.bg-whatsapp-dark {
    background-color: #075E54;
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Better focus for accessibility */
.focus-visible:focus {
    outline: 3px solid #6366f1;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

/* Hide scrollbar when not needed */
.overflow-y-auto {
    -webkit-overflow-scrolling: touch;
}

.overflow-y-auto::-webkit-scrollbar {
    display: none;
}

.overflow-y-auto {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Virtual scroll performance optimization */
[x-ref="scrollContainer"] > * {
    contain: content;
}
</style>

@endsection