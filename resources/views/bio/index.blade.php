@extends('layouts.index')

@section('title', 'Bio Pages')

@section('content')
<div id="pageContainer" class="max-w-7xl mx-auto px-6">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@if($createMode)
<script>
  window.addEventListener('DOMContentLoaded', () => {

    // 🔥 reset socials
    const socials = document.getElementById('livePreviewSocials');
    if (socials) socials.innerHTML = '';

    // 🔥 reset preview screen styles
    const screen = document.getElementById('livePreviewScreen');
    if (screen) {
        screen.removeAttribute('style');
    }

    // 🔥 reset css variable (fallback)
    document.documentElement.style.removeProperty('--primary-color');
});

</script>
@endif
{{-- SUCCESS/ERROR MESSAGES --}}
@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
    <ul class="list-disc pl-5 text-sm">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if($editingBio)

    @include('bio.tabs.content')
@else
    {{-- LIST VIEW --}}
    <div class="space-y-6 sm:space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Bio Pages</h1>
                <div class="flex items-center gap-3 sm:gap-4 mt-2 flex-wrap">
                    <span class="text-xs sm:text-sm text-gray-600">
                        {{ $bios->count() }} Bio Pages / 75
                    </span>
                    <span class="text-xs sm:text-sm text-gray-400 hidden sm:inline">•</span>
                    <span class="text-xs sm:text-sm text-gray-600">
                        {{ $bios->sum('views') ?? 0 }} Total Views
                    </span>
                </div>
            </div>

            <button onclick="openCreateModal()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 sm:px-5 py-2.5 rounded-lg font-medium text-sm sm:text-base flex items-center justify-center gap-2 transition duration-200 shadow-sm hover:shadow w-full sm:w-auto">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Bio
            </button>
        </div>

      {{-- SEARCH --}}
<form method="GET" class="relative max-w-md">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="Search for Bio Pages"
           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">

    <svg class="absolute left-3 top-3.5 w-4 h-4 sm:w-5 sm:h-5 text-gray-400"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
</form>


        {{-- BIO PAGES GRID --}}
        @if($bios->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($bios as $bio)
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-5 hover:shadow-md transition duration-200">
                <div class="flex items-start justify-between mb-3 sm:mb-4 flex-col sm:flex-row gap-2">
                    <div class="w-full">
                        <h3 class="font-semibold text-gray-900 text-base sm:text-lg mb-2">{{ $bio->title }}</h3>
                        @if(!empty($bio->slug))
                        <a href="{{ route('bio.view', $bio->slug) }}" target="_blank" class="text-xs sm:text-sm text-purple-600 hover:text-purple-800 break-all">
                            {{ bio_domain() }}/{{ $bio->slug }}

                        </a>
                        @endif
                    </div>
                    @if($bio->is_active)
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full w-fit mt-2 sm:mt-0">Active</span>
                    @else
                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full w-fit mt-2 sm:mt-0">Inactive</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5 flex-wrap">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $bio->views ?? 0 }} views
                    </span>
                    <span class="hidden sm:inline">•</span>
                    <span>{{ $bio->created_at->diffForHumans() }}</span>
                </div>
<div class="flex gap-2 flex-col sm:flex-row">
    
    {{-- EDIT --}}
    <a href="{{ route('bio.edit', $bio->_id) }}"
       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg
              text-xs sm:text-sm font-medium text-gray-700
              hover:bg-gray-50 text-center transition duration-200">
        Edit
    </a>
    
    {{-- VIEW --}}
    @if(!empty($bio->slug))
    <a href="{{ route('bio.view', $bio->slug) }}"
       target="_blank"
       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg
              text-xs sm:text-sm font-medium text-green-700
              hover:bg-green-50 text-center transition duration-200">
        View
    </a>
    @endif

    {{-- DELETE --}}
    <form method="POST"
          action="{{ route('bio.delete') }}"
          class="flex-1"
          onsubmit="return confirm('Are you sure you want to delete this bio page? This action cannot be undone.')">
        @csrf
        <input type="hidden" name="id" value="{{ $bio->_id }}">

        <button type="submit"
                class="w-full px-3 py-2 border border-red-300 rounded-lg
                       text-xs sm:text-sm font-medium text-red-600
                       hover:bg-red-50 text-center transition duration-200">
            Delete
        </button>
    </form>

</div>

            </div>
            @endforeach
        </div>
        @else
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-xl shadow-sm border p-6 sm:p-8 md:p-10 text-center">
            <div class="w-14 h-14 sm:w-16 sm:h-16 md:w-20 md:h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No bio pages yet</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6 max-w-md mx-auto">
                Create your first bio page to share all your important links in one place.
            </p>
            <button onclick="openCreateModal()"
                    class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 sm:px-5 py-2.5 rounded-lg shadow-sm hover:shadow transition duration-200 w-full sm:w-auto text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Your First Bio
            </button>
        </div>
        @endif

    </div>

    {{-- CREATE BIO MODAL --}}
    <div id="createBioModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            {{-- OVERLAY --}}
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeCreateModal()"></div>
            
            {{-- MODAL --}}
            <div class="inline-block w-full max-w-md p-4 sm:p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl">
                
                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Create New Bio</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                {{-- FORM --}}
                <form method="POST" action="{{ route('bio.save') }}" id="createBioForm">
                    @csrf
                    
                    <div class="space-y-4">
                        {{-- Bio Page Name --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                Bio Page Name
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="bioTitle"
                                   required
                                   placeholder="e.g. My Personal Bio"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">
                        </div>
                        
                        {{-- Bio Page Alias --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                Bio Page Alias
                            </label>
                            <div class="flex flex-col sm:flex-row">
                                <span class="px-3 py-2.5 bg-gray-100 border border-gray-300 rounded-t-lg sm:rounded-l-lg sm:rounded-tr-none sm:border-r-0 text-xs sm:text-sm text-gray-600">
                                    {{ bio_domain() }}/
                                </span>
                                <input type="text" 
                                       name="alias" 
                                       id="bioAlias"
                                       placeholder="your-alias"
                                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-b-lg sm:rounded-r-lg sm:rounded-bl-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm sm:text-base">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Leave empty to generate a random alias.
                            </p>
                        </div>
                    </div>
                    
                    {{-- FOOTER --}}
                    <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <button type="button"
                                onclick="closeCreateModal()"
                                class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 order-2 sm:order-1">
                            Cancel
                        </button>
                        
                        <button type="submit"
                                class="px-3 sm:px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg text-xs sm:text-sm order-1 sm:order-2">
                            Create Bio
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

@endif
<div id="addContentModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
        <div class="bg-white w-full max-w-5xl rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden">
            <div class="sticky top-0 bg-white border-b p-3 sm:p-4 md:p-6 z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900">Add Link or Content</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Choose content type to add to your bio page</p>
                    </div>
                    <button onclick="closeAddContent()" 
                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-3 sm:p-4 md:p-6 max-h-[60vh] overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 md:gap-4">
                    
                    @foreach([
                        'Link','Tagline','Heading','Text','Divider','HTML',
                        'Image','Phone Call','WhatsApp Call','WhatsApp Message',
                        'Video','Audio','PDF','YouTube','Spotify','Instagram',
                        'TikTok','Google Maps','Contact Form','Newsletter','FAQs'
                    ] as $item)
                    <div
                        onclick="
                            @if($item === 'Link')
                                addLinkBlock()
                            @elseif($item === 'Tagline')
                                addTaglineBlock()
                            @elseif($item === 'Heading')
                                addHeadingBlock()
                            @elseif($item === 'Text') 
                                addTextBlock()
                            @elseif($item === 'Divider')
                                addDividerBlock()
                             @elseif($item === 'HTML')
                                addHtmlBlock()
                            @elseif($item === 'Image')
                                addImageBlock()
                             @elseif($item === 'Phone Call')
                                addPhoneBlock()
                            @elseif($item === 'WhatsApp Call')
                                addWhatsappCallBlock()
                            @elseif($item === 'WhatsApp Message')
                                addWhatsappMessageBlock()
                            @elseif($item === 'Video')
                                addVideoBlock()
                            @elseif($item === 'Audio')
                                addAudioBlock()
                            @elseif($item === 'PDF')
                                addPdfBlock()
                            @elseif($item === 'YouTube')
                                addYoutubeBlock()
                            @elseif($item === 'Spotify')
                                addSpotifyBlock()
                            @elseif($item === 'Instagram')
                                addInstagramBlock()
                            @elseif($item === 'Google Maps')
                                addGoogleMapsBlock()
                            @elseif($item === 'FAQs')
                                addFaqBlock()
                            @elseif($item === 'Contact Form')
                                addContactFormBlock()
                            @elseif($item === 'Newsletter')
                                addNewsletterBlock()
                            @endif"
                        class="border border-gray-200 rounded-lg md:rounded-xl p-3 sm:p-4 hover:bg-purple-50 hover:border-purple-200 cursor-pointer transition duration-200 group"
                    >
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition duration-200">
                                @switch($item)
                                    @case('Link')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        @break
                                    @case('Tagline')
                                        <span class="text-gray-600 group-hover:text-purple-600 text-xs sm:text-sm md:text-base">🏷️</span>
                                        @break
                                    @case('Heading')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                        @break
                                    @case('Text')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        @break
                                    @case('Divider')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                        @break
                                    @case('Image')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                    @case('Phone Call')
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        @break
                                    @case('WhatsApp')
                                    @case('WhatsApp Call')
                                    @case('WhatsApp Message')
                                        <span class="text-gray-600 group-hover:text-purple-600 text-xs sm:text-sm md:text-base">💬</span>
                                        @break
                                    @default
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 group-hover:text-purple-700 text-xs sm:text-sm md:text-base truncate">{{ $item }}</h4>
                                <p class="text-xs text-gray-500 mt-1 truncate">Add {{ strtolower($item) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>
            <div class="border-t p-3 sm:p-4 bg-gray-50">
                <div class="flex justify-end">
                    <button onclick="closeAddContent()"
                            class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-100 w-full sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script>
function addTextBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="p-4 font-medium text-sm sm:text-base">Text</div>

        <div class="border-t p-4 space-y-3">
            <div id="editor-${index}" class="bg-white min-h-[150px] sm:min-h-[200px]"></div>

            <input type="hidden" name="links[${index}][text]" id="input-${index}">
            <input type="hidden" name="links[${index}][type]" value="text">
        </div>
    `;

    container.appendChild(block);

    const quill = new Quill('#editor-' + index, {
        theme: 'snow',
        placeholder: 'Write something...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    quill.on('text-change', function () {
        document.getElementById('input-' + index).value = quill.root.innerHTML;
    });

    closeAddContent();
}
</script>

<script>
function addHeadingBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer" onclick="toggleBlock(this)">
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold">H</span>
                <div class="min-w-0">
                    <div class="font-medium text-gray-900 truncate text-sm sm:text-base">Heading</div>
                    <div class="text-xs text-gray-500 truncate">New heading</div>
                </div>
            </div>
        </div>

        <div class="border-t p-4 space-y-4">

            <!-- STYLE -->
            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Style</label>
                <select name="links[${index}][style]"
                        class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base">
                    <option value="h1">H1</option>
                    <option value="h2">H2</option>
                    <option value="h3">H3</option>
                    <option value="h4">H4</option>
                    <option value="h5" selected>H5</option>
                    <option value="h6">H6</option>
                </select>
            </div>

            <!-- TEXT -->
            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Text</label>
                <input type="text"
                       name="links[${index}][text]"
                       class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                       placeholder="Heading text"
                       oninput="updateHeadingPreview(this.value)">
            </div>

            <!-- COLOR -->
            <div class="flex items-center gap-3">
                <label class="text-xs sm:text-sm font-medium text-gray-700">Color:</label>
                <input type="color"
                       name="links[${index}][color]"
                       value="#000000"
                       class="w-8 h-8 sm:w-10 sm:h-10 cursor-pointer">
            </div>

            <input type="hidden" name="links[${index}][type]" value="heading">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>

<script>
function updatePreview() {
    const nameInput = document.getElementById('bioName');
    const previewName = document.getElementById('previewName');
    
    if (nameInput && previewName) {
        previewName.textContent = nameInput.value || 'Your Bio';
    }
}

function updateLinkPreviewText(value) {
    const el = document.getElementById('previewLinkText');
    if (el) {
        el.textContent = value || 'Your link text';
    }
}

function updateLinkPreviewUrl(value) {
    const el = document.getElementById('previewLink');
    if (el) {
        el.href = value || '#';
    }
}

function updateTaglineLive(value) {
    const preview = document.getElementById('previewTagline');
    if (!preview) return;

    if (value.trim() === '') {
        preview.classList.add('hidden');
        preview.textContent = '';
    } else {
        preview.textContent = value;
        preview.classList.remove('hidden');
    }
}
function openCreateModal() {
    const modal = document.getElementById('createBioModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('block');
        document.body.classList.add('overflow-hidden');
        
        setTimeout(() => {
            const titleInput = document.getElementById('bioTitle');
            if (titleInput) titleInput.focus();
        }, 100);
    }
}

function closeCreateModal() {
    const modal = document.getElementById('createBioModal');
    if (modal) {
        modal.classList.remove('block');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

function openAddContent() {
    const modal = document.getElementById('addContentModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
}

function closeAddContent() {
    const modal = document.getElementById('addContentModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}
function toggleBlock(header) {
    const body = header.nextElementSibling;
    body.classList.toggle('hidden');
}

function addLinkBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';
    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer" onclick="toggleBlock(this)">
            <div class="flex items-center gap-3 min-w-0">
                <span class="text-lg">🔗</span>
                <div class="min-w-0">
                    <div class="font-medium text-gray-900 truncate text-sm sm:text-base">Link</div>
                    <div class="text-xs text-gray-500 truncate">New link</div>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-xs text-gray-400">0 Clicks</span>
                <input type="checkbox" checked>
            </div>
        </div>
        <div class="border-t p-4 space-y-4">
            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Text</label>
                <input type="text" name="links[${index}][text]" class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                       placeholder="e.g. Follow me on Instagram" oninput="updateLinkPreviewText(this.value)">
            </div>
            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Link</label>
                <input type="url" name="links[${index}][url]" class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                       placeholder="https://instagram.com" oninput="updateLinkPreviewUrl(this.value)">
            </div>
            <input type="hidden" name="links[${index}][type]" value="link">
        </div>
    `;
    container.appendChild(block);
    closeAddContent();
}

function addTaglineBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer" onclick="toggleBlock(this)">
            <div class="flex items-center gap-3 min-w-0">
                <span class="text-lg">📝</span>
                <div class="min-w-0">
                    <div class="font-medium text-gray-900 truncate text-sm sm:text-base">Tagline</div>
                    <div class="text-xs text-gray-500 truncate">—</div>
                </div>
            </div>
        </div>

        <div class="border-t p-4 space-y-4">
            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Tagline</label>

                <input type="text"
                       name="links[${index}][text]"
                       class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                       placeholder="e.g. Digital Creator"
                       oninput="updateTaglineLive(this.value)">

                <input type="hidden" name="links[${index}][type]" value="tagline">
            </div>
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeAddContent();
    }
});

const createModal = document.getElementById('createBioModal');
if (createModal) {
    createModal.addEventListener('click', function(e) {
        if (e.target.id === 'createBioModal') {
            closeCreateModal();
        }
    });
}

const addContentModal = document.getElementById('addContentModal');
if (addContentModal) {
    addContentModal.addEventListener('click', function(e) {
        if (e.target.id === 'addContentModal') {
            closeAddContent();
        }
    });
}
</script>
<script>
function toggleDivider(header) {
    const body = header.nextElementSibling;
    if (!body) return;

    body.classList.toggle('hidden');

    const icon = header.querySelector('svg');
    if (icon) {
        icon.classList.toggle('rotate-180');
    }
}
</script>

<style>
#addContentModal .overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #c7d2fe transparent;
}

#addContentModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#addContentModal .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#addContentModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 3px;
}

#addContentModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a5b4fc;
}
@media (max-width: 640px) {
    .xs\:flex-row {
        flex-direction: row;
    }
}

@media (max-width: 768px) {
    .lg\:grid-cols-\[1fr_380px\] {
        grid-template-columns: 1fr;
    }
    .sticky {
        position: static !important;
    }
    
    .relative.mx-auto {
        margin-left: auto;
        margin-right: auto;
    }
}
@media (max-width: 640px) {
    button, a, input[type="checkbox"] {
        min-height: 44px;
        min-width: 44px;
    }
    
    select, input[type="text"], input[type="url"] {
        font-size: 16px; 
    }
}
</style>
<script>
function addDividerBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="p-4 font-medium text-sm sm:text-base">Divider</div>

        <div class="border-t p-4 space-y-4">

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Style</label>
                <select name="links[${index}][style]"
                        class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="solid">Solid</option>
                    <option value="dashed">Dashed</option>
                    <option value="dotted">Dotted</option>
                </select>
            </div>

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Height</label>
                <input type="range"
                       min="1" max="10"
                       value="1"
                       name="links[${index}][height]"
                       class="w-full">
            </div>

            <div class="flex items-center gap-3">
                <label class="text-xs sm:text-sm font-medium text-gray-700">Color</label>
                <input type="color"
                       name="links[${index}][color]"
                       value="#000000"
                       class="w-8 h-8 sm:w-10 sm:h-10 cursor-pointer">
            </div>

            <input type="hidden" name="links[${index}][type]" value="divider">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addHtmlBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer"
             onclick="toggleBlock(this)">
            <span class="font-medium text-sm sm:text-base">&lt;/&gt; HTML</span>
        </div>

        <div class="border-t p-4 space-y-3 block-body">
            <textarea
                name="links[${index}][text]"
                rows="4"
                class="w-full px-3 py-2 border rounded-lg text-sm sm:text-base"
                placeholder="<div>Custom HTML</div>"
            ></textarea>

            <input type="hidden" name="links[${index}][type]" value="html">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addImageBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer"
             onclick="toggleBlock(this)">
            <span class="font-medium text-sm sm:text-base">🖼 Image</span>
        </div>

        <div class="border-t p-4 space-y-4 block-body">

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">
                    Image File
                </label>
                <input type="file"
                       name="links[${index}][file]"
                       class="w-full mt-1 text-sm">
            </div>

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">
                    Link (optional)
                </label>
                <input type="url"
                       name="links[${index}][url]"
                       class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                       placeholder="https://example.com">
            </div>

            <input type="hidden" name="links[${index}][type]" value="image">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addPhoneBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm block-wrapper';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer"
             onclick="toggleBlock(this)">
            <div class="flex items-center gap-3">
                <span class="text-lg">📞</span>
                <span class="font-medium text-sm sm:text-base">Phone Call</span>
            </div>
            <svg class="w-4 h-4 text-gray-400 transition-transform">
                <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
        </div>

        <div class="border-t p-4 space-y-3 block-body">

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text"
                       name="links[${index}][phone]"
                       class="w-full mt-1 px-3 py-2 border rounded-lg text-sm"
                       placeholder="851936683"
                       oninput="updatePhonePreview(this.value)">
            </div>

            <div>
                <label class="text-xs sm:text-sm font-medium text-gray-700">Label</label>
                <input type="text"
                       name="links[${index}][label]"
                       class="w-full mt-1 px-3 py-2 border rounded-lg text-sm"
                       placeholder="Call Me"
                       oninput="updatePhoneLabelPreview(this.value)">
            </div>

            <input type="hidden" name="links[${index}][type]" value="phone_call">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addVideoBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm">
            <div class="p-4 font-medium">🎥 Video</div>

            <div class="border-t p-4 space-y-4">
                <input type="file"
                       name="links[${index}][file]"
                       accept="video/*">

                <input type="url"
                       name="links[${index}][url]"
                       placeholder="External link (optional)"
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="hidden" name="links[${index}][type]" value="video">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function addAudioBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm">
            <div class="p-4 font-medium">🔊 Audio</div>

            <div class="border-t p-4 space-y-4">
                <input type="file"
                       name="links[${index}][file]"
                       accept="audio/*">

                <input type="hidden" name="links[${index}][type]" value="audio">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>

<script>
function updatePhonePreview(value) {
    const el = document.getElementById('previewPhoneLink');
    if (el) {
        el.href = value ? `tel:${value}` : '#';
    }
}

function updatePhoneLabelPreview(value) {
    const el = document.getElementById('previewPhoneLabel');
    if (el) {
        el.textContent = value || 'Call';
    }
}
</script>
<script>
function addWhatsappCallBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer"
             onclick="toggleBlock(this)">
            <span class="font-medium text-sm">📞 WhatsApp Call</span>
        </div>

        <div class="border-t p-4 space-y-3">
            <input type="text"
                   name="links[${index}][phone]"
                   class="w-full px-3 py-2 border rounded-lg text-sm"
                   placeholder="WhatsApp number">

            <input type="text"
                   name="links[${index}][label]"
                   class="w-full px-3 py-2 border rounded-lg text-sm"
                   placeholder="Button label (Call me)">

            <input type="hidden" name="links[${index}][type]" value="whatsapp_call">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addPdfBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm">
            <div class="p-4 font-medium">📄 PDF Document</div>

            <div class="border-t p-4 space-y-3">
                <input type="text"
                       name="links[${index}][title]"
                       placeholder="Document title"
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="file"
                       name="links[${index}][file]"
                       accept="application/pdf">

                <input type="hidden"
                       name="links[${index}][type]"
                       value="pdf">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function addYoutubeBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm">
            <div class="p-4 font-medium">▶️ YouTube Video</div>

            <div class="border-t p-4">
                <input type="url"
                       name="links[${index}][url]"
                       placeholder="https://youtube.com/watch?v=..."
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="hidden"
                       name="links[${index}][type]"
                       value="youtube">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function addSpotifyBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm">
            <div class="p-4 font-medium">🎵 Spotify Embed</div>

            <div class="border-t p-4">
                <input type="url"
                       name="links[${index}][url]"
                       placeholder="https://open.spotify.com/..."
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="hidden"
                       name="links[${index}][type]"
                       value="spotify">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>

<script>
function addWhatsappMessageBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    const block = document.createElement('div');
    block.className = 'bg-white border rounded-xl shadow-sm';

    block.innerHTML = `
        <div class="flex items-center justify-between p-4 cursor-pointer"
             onclick="toggleBlock(this)">
            <span class="font-medium text-sm">💬 WhatsApp Message</span>
        </div>

        <div class="border-t p-4 space-y-3">
            <input type="text"
                   name="links[${index}][phone]"
                   class="w-full px-3 py-2 border rounded-lg text-sm"
                   placeholder="WhatsApp number">

            <input type="text"
                   name="links[${index}][label]"
                   class="w-full px-3 py-2 border rounded-lg text-sm"
                   placeholder="Button label">

            <textarea
                name="links[${index}][message]"
                class="w-full px-3 py-2 border rounded-lg text-sm"
                placeholder="Default message (optional)"></textarea>

            <input type="hidden" name="links[${index}][type]" value="whatsapp_message">
        </div>
    `;

    container.appendChild(block);
    closeAddContent();
}
</script>
<script>
function addInstagramBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm block-wrapper">
            <div class="flex items-center justify-between p-4 font-medium">
                📸 Instagram Post
            </div>

            <div class="border-t p-4 space-y-3">
                <input type="url"
                       name="links[${index}][url]"
                       placeholder="https://www.instagram.com/p/..."
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="hidden" name="links[${index}][type]" value="instagram">
                <input type="hidden" name="links[${index}][enabled]" value="1">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>

<script>
function addGoogleMapsBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm block-wrapper">
            <div class="flex items-center justify-between p-4 font-medium">
                📍 Google Maps
            </div>

            <div class="border-t p-4 space-y-3">
                <input type="text"
                       name="links[${index}][address]"
                       placeholder="e.g. Apple Park, Cupertino"
                       class="w-full px-3 py-2 border rounded-lg">

                <input type="hidden" name="links[${index}][type]" value="maps">
                <input type="hidden" name="links[${index}][enabled]" value="1">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function addFaqBlock() {
    const container = document.getElementById('contentBlocks');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm block-wrapper">

            <div class="flex items-center justify-between p-4 cursor-pointer"
                 onclick="toggleBlock(this)">
                <span class="font-medium text-sm">❓ FAQ</span>
                <svg class="w-4 h-4 text-gray-400 transition-transform">
                    <path d="M6 9l6 6 6-6"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2"/>
                </svg>
            </div>

            <div class="border-t p-4 space-y-3 block-body">
                <input type="text"
                       name="links[${index}][question]"
                       placeholder="Question"
                       class="w-full px-3 py-2 border rounded-lg text-sm">

                <textarea
                    name="links[${index}][answer]"
                    rows="3"
                    placeholder="Answer"
                    class="w-full px-3 py-2 border rounded-lg text-sm"
                ></textarea>

                <input type="hidden" name="links[${index}][type]" value="faq">
                <input type="hidden" name="links[${index}][enabled]" value="1">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function addContactFormBlock() {
    const c = document.getElementById('contentBlocks');
    const i = c.children.length;

    c.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm block-wrapper">
            <div class="flex items-center justify-between p-4 cursor-pointer"
                 onclick="toggleBlock(this)">
                <span class="font-medium text-sm">📩 Contact Form</span>
                <span>⌄</span>
            </div>

            <div class="border-t p-4 space-y-3 block-body">
                <input type="text"
                       name="links[${i}][text]"
                       placeholder="Heading"
                       class="w-full px-3 py-2 border rounded-lg text-sm">

                <textarea
                    name="links[${i}][disclaimer]"
                    rows="2"
                    placeholder="Disclaimer (optional)"
                    class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>

                <input type="hidden" name="links[${i}][type]" value="contact_form">
                <input type="hidden" name="links[${i}][enabled]" value="1">
            </div>
        </div>
    `);

    closeAddContent();
}

function addNewsletterBlock() {
    const c = document.getElementById('contentBlocks');
    const i = c.children.length;

    c.insertAdjacentHTML('beforeend', `
        <div class="bg-white border rounded-xl shadow-sm block-wrapper">
            <div class="flex items-center justify-between p-4 cursor-pointer"
                 onclick="toggleBlock(this)">
                <span class="font-medium text-sm">📬 Newsletter</span>
                <span>⌄</span>
            </div>

            <div class="border-t p-4 space-y-3 block-body">
                <input type="text"
                       name="links[${i}][text]"
                       placeholder="Button text"
                       class="w-full px-3 py-2 border rounded-lg text-sm">

                <textarea
                    name="links[${i}][description]"
                    rows="2"
                    placeholder="Description"
                    class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>

                <textarea
                    name="links[${i}][disclaimer]"
                    rows="2"
                    placeholder="Disclaimer"
                    class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>

                <input type="hidden" name="links[${i}][type]" value="newsletter">
                <input type="hidden" name="links[${i}][enabled]" value="1">
            </div>
        </div>
    `);

    closeAddContent();
}
</script>
<script>
function switchBioTab(tab) {
    document.querySelectorAll('[data-bio-tab]').forEach(el => {
        el.classList.add('hidden');
    });

    const activeTab = document.getElementById('bio-tab-' + tab);
    if (activeTab) {
        activeTab.classList.remove('hidden');
    }
    document.querySelectorAll('[data-bio-tab-btn]').forEach(btn => {
        btn.classList.remove('bg-black', 'text-white');
        btn.classList.add('text-gray-600');
    });

    const activeBtn = document.querySelector(`[data-bio-tab-btn="${tab}"]`);
    if (activeBtn) {
        activeBtn.classList.add('bg-black', 'text-white');
        activeBtn.classList.remove('text-gray-600');
    }
    const preview = document.getElementById('livePreviewWrapper');
    if (preview) {
        tab === 'statistics'
            ? preview.classList.add('hidden')
            : preview.classList.remove('hidden');
    }
    const form = document.getElementById('bioEditForm');
    if (form) {
        if (tab === 'statistics') {
            form.classList.remove('md:grid-cols-[1fr_380px]');
            form.classList.add('md:grid-cols-1');
        } else {
            form.classList.add('md:grid-cols-[1fr_380px]');
            form.classList.remove('md:grid-cols-1');
        }
    }
}
</script>
<script>
window.addEventListener('designUpdated', function (e) {

    const { key, value } = e.detail;
    const screen = document.getElementById('livePreviewScreen');
    if (!screen) return;
    if (key === 'header_layout') {
        screen.setAttribute('data-header-layout', value);
    }
    if (key === 'theme') {
        screen.style.background = value;
    }
    if (key === 'background_value') {
        screen.style.background = value;
    }
    if (key === 'font') {
        screen.classList.remove(
            'font-sans','font-serif','font-mono',
            'tracking-wide','italic','uppercase'
        );
        screen.classList.add(value);
    }
    if (key === 'text_color') {
        screen.style.color = value;
    }
    if (key === 'button_color') {
        screen.querySelectorAll('.preview-button').forEach(btn => {
            btn.style.backgroundColor = value;
        });
    }
    if (key === 'button_text_color') {
        screen.querySelectorAll('.preview-button').forEach(btn => {
            btn.style.color = value;
        });
    }
    if (key === 'button_radius') {
        screen.querySelectorAll('.preview-button').forEach(btn => {
            btn.classList.remove('rounded-lg','rounded-none','rounded-full');
            btn.classList.add(value);
        });
    }
    if (key === 'button_shadow') {
        screen.querySelectorAll('.preview-button').forEach(btn => {
            btn.classList.remove('shadow-md','shadow-xl');
            if (value) btn.classList.add(value);
        });
    }

});
</script>
@if(session('reset_preview'))
<script>
    window.__RESET_BIO_PREVIEW__ = true;
</script>
@endif

</div>
@endsection