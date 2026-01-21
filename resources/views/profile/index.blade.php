@extends('layouts.index')

@section('title','My Profile')

@section('content')
<div x-data="{ 
    edit: false,
    saveLoading: false,

    original: {
        name: @js($user->name),
        phone: @js($user->phone),
        address: @js($user->address),
        bio: @js($user->bio),
        avatar: '{{ $user->avatar ? asset('storage/'.$user->avatar) : null }}'
    },

    resetPreview() {
        // restore form values
        document.querySelector('input[name=name]').value = this.original.name;
        document.querySelector('input[name=phone]').value = this.original.phone ?? '';
        document.querySelector('textarea[name=address]').value = this.original.address ?? '';
        document.querySelector('textarea[name=bio]').value = this.original.bio ?? '';

        // restore avatar
        const preview = document.getElementById('avatarPreview');
        if (this.original.avatar && preview.tagName === 'IMG') {
            preview.src = this.original.avatar;
        }
    }
}" class="max-w-4xl mx-auto mt-8 sm:mt-12 px-4">

    
    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/60 overflow-hidden">
        
        {{-- CARD HEADER --}}
        <div class="px-8 py-6 bg-gradient-to-r from-[#F8F9FF] to-white border-b border-gray-200/50">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                {{-- LEFT SECTION --}}
                <div class="flex items-center gap-5">
                    <div class="relative cursor-pointer group"
                         @click="edit && document.getElementById('avatarInput').click()">
                        @if($user->avatar)
                            <img id="avatarPreview"
                                 src="{{ asset('storage/'.$user->avatar) }}"
                                 class="w-20 h-20 sm:w-24 sm:h-24 rounded-full
                                        object-cover border-4 border-white shadow-xl">
                        @else
                            <div id="avatarPreview"
                                 class="w-20 h-20 sm:w-24 sm:h-24 rounded-full
                                        bg-gradient-to-br from-[#4C6FFF] to-[#6A5FFF]
                                        flex items-center justify-center text-white
                                        text-2xl sm:text-3xl font-bold
                                        border-4 border-white shadow-xl">
                                {{ strtoupper(substr($user->name,0,2)) }}
                            </div>
                        @endif
                        
                        <div x-show="edit"
                             class="absolute inset-0 bg-black/40 rounded-full 
                                    opacity-0 group-hover:opacity-100 transition-all duration-300
                                    flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/>
                                <path d="M11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- NAME & EMAIL --}}
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                            {{ $user->name }}
                        </h2>
                        <p class="text-sm text-gray-600 flex items-center gap-2 mt-2">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ $user->email }}
                        </p>
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $user->role ?? 'User' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTON --}}
                <button @click="edit = true"
                        x-show="!edit"
                        class="px-6 py-3 rounded-xl text-sm font-semibold
                               bg-gradient-to-r from-[#4C6FFF] to-[#6A5FFF] text-white
                               hover:shadow-lg transform hover:-translate-y-0.5
                               transition-all duration-300
                               flex items-center gap-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11
                                 a2 2 0 002-2v-5m-1.414-9.414
                                 a2 2 0 112.828 2.828L11.828 15H9
                                 v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </button>
            </div>
        </div>

        {{-- CONTENT AREA --}}
        <div class="p-8">
            {{-- VIEW MODE --}}
            <div x-show="!edit" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="space-y-10">
                
                {{-- GRID LAYOUT --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    {{-- CONTACT SECTION --}}
                    <div class="space-y-7">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-200/60">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-900">Contact Information</h3>
                                <p class="text-sm text-gray-500 mt-1">Your personal contact details</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-gray-50/80 rounded-xl p-5 border border-gray-200/50 hover:bg-gray-100/50 transition-colors">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Phone</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ $user->phone ?: 'Not provided' }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50/80 rounded-xl p-5 border border-gray-200/50 hover:bg-gray-100/50 transition-colors">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Address</p>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $user->address ?: 'Not provided' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ABOUT SECTION --}}
                    <div class="space-y-7">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-200/60">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-900">About</h3>
                                <p class="text-sm text-gray-500 mt-1">Personal information</p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50/80 rounded-xl p-6 border border-gray-200/50 hover:bg-gray-100/50 transition-colors">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-4">Bio</p>
                            <div class="text-gray-700 leading-relaxed bg-white rounded-xl p-5 border border-gray-200/60">
                                {{ $user->bio ?: 'No bio provided yet. Tell us a bit about yourself!' }}
                            </div>
                            <div class="mt-6 pt-6 border-t border-gray-200/50">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Profile created</span>
                                    <span class="font-medium text-gray-900">{{ $user->created_at->format('d M, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- EDIT MODE --}}
            <form x-show="edit"
                  x-cloak
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 translate-y-4"
                  x-transition:enter-end="opacity-100 translate-y-0"
                  method="POST"
                  action="{{ route('profile.update') }}"
                  enctype="multipart/form-data"
                  @submit="saveLoading = true"
                  class="space-y-8">
                @csrf

                <input type="file"
                       id="avatarInput"
                       name="avatar"
                       accept="image/*"
                       class="hidden"
                       onchange="previewAvatar(event)">

                {{-- FORM FIELDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                Full Name
                            </label>
                            <input type="text" name="name"
                                   value="{{ $user->name }}"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3.5 
                                          focus:ring-2 focus:ring-[#4C6FFF] focus:border-[#4C6FFF] 
                                          outline-none transition-all duration-200
                                          hover:border-gray-400 text-gray-900"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                Phone Number
                            </label>
                            <input type="tel"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   class="w-full rounded-xl border border-gray-300 px-4 py-3.5 
                                          focus:ring-2 focus:ring-[#4C6FFF] focus:border-[#4C6FFF] 
                                          outline-none transition-all duration-200
                                          hover:border-gray-400 text-gray-900"
                                   placeholder="Enter 10 digit phone number">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                Address
                            </label>
                            <textarea name="address"
                                      rows="3"
                                      class="w-full rounded-xl border border-gray-300 px-4 py-3.5 
                                             focus:ring-2 focus:ring-[#4C6FFF] focus:border-[#4C6FFF] 
                                             outline-none transition-all duration-200 resize-none
                                             hover:border-gray-400 text-gray-900"
                                      placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-sm text-red-600 mt-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                Bio
                            </label>
                            <textarea name="bio" rows="4"
                                      class="w-full rounded-xl border border-gray-300 px-4 py-3.5 
                                             focus:ring-2 focus:ring-[#4C6FFF] focus:border-[#4C6FFF] 
                                             outline-none transition-all duration-200 resize-none
                                             hover:border-gray-400 text-gray-900"
                                      placeholder="Tell us a bit about yourself...">{{ $user->bio }}</textarea>
                            <p class="text-xs text-gray-400 mt-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Brief description for your profile.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- FORM ACTIONS --}}
                <div class="pt-8 mt-8 border-t border-gray-200/60 flex justify-between">
                   <button type="button"
                        @click="
                            edit = false;
                            resetPreview();
                        "
                        class="px-7 py-3.5 rounded-xl bg-gray-100 text-gray-700 font-semibold
                            hover:bg-gray-200 transition-all duration-200
                            flex items-center gap-2 border border-gray-200
                            hover:shadow-sm">
                    Back
                </button>


                    <button type="submit"
                            :disabled="saveLoading"
                            :class="saveLoading ? 'opacity-70 cursor-not-allowed' : ''"
                            class="px-8 py-3.5 rounded-xl font-semibold 
                                   bg-gradient-to-r from-[#4C6FFF] to-[#6A5FFF] text-white
                                   hover:shadow-lg transform hover:-translate-y-0.5
                                   transition-all duration-300 
                                   flex items-center gap-2 shadow-md">
                        <svg x-show="saveLoading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- PREVIEW SCRIPT --}}
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.match('image.*')) return;

    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatarPreview');
        if (preview && preview.tagName === 'IMG') {
            preview.src = e.target.result;
        }
    };
    reader.readAsDataURL(file);
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection