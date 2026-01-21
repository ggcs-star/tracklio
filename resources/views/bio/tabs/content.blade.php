{{-- resources/views/bio/tabs/content.blade.php --}}
 <script>
        window.CURRENT_BIO_ID = "{{ $editingBio->_id }}";
    </script>
<div class="space-y-6 sm:space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Customize {{ $editingBio->title }}</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Edit your bio page content and settings</p>
        </div>

        <div class="flex flex-col xs:flex-row gap-2 sm:gap-3">
            @if($editingBio && $editingBio->slug)
            <a href="{{ route('bio.view', $editingBio->slug) }}" target="_blank"
               class="px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs sm:text-sm font-medium text-gray-700 transition duration-200 text-center">
                Preview Bio
            </a>
            @endif

            <a href="{{ route('bio.index') }}"
               class="px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs sm:text-sm font-medium text-gray-700 transition duration-200 text-center">
                Back to List
            </a>
        </div>
    </div>

    {{-- TABS --}}
{{-- TABS --}}
<div class="bg-white rounded-xl shadow-sm border p-2 overflow-x-auto">
    <div class="flex gap-2 min-w-max">
        <button
            data-bio-tab-btn="content"
            onclick="switchBioTab('content')"
            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium bg-black text-white rounded-lg">
            Content
        </button>
        <button
            data-bio-tab-btn="social-links"
            onclick="switchBioTab('social-links')"
            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition duration-200 whitespace-nowrap">
            Social Links
        </button>
        <button
            data-bio-tab-btn="design"
            onclick="switchBioTab('design')"
            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition duration-200 whitespace-nowrap">
            Design
        </button>
        <button
            data-bio-tab-btn="settings"
            onclick="switchBioTab('settings')"
            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition duration-200 whitespace-nowrap">
            Settings
        </button>
         <button
            data-bio-tab-btn="statistics"
            onclick="switchBioTab('statistics')"
            class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 transition duration-200 whitespace-nowrap">
            Statistics
        </button>
       
    </div>
</div>
   <form
    id="bioEditForm"
    method="POST"
    action="{{ route('bio.save') }}"
    enctype="multipart/form-data"
    class="grid grid-cols-1 md:grid-cols-[1fr_380px] gap-8 items-start"
>

        @csrf
        <input type="hidden" name="id" value="{{ $editingBio->_id }}">
           <input type="hidden" name="design" id="design_json">

       {{-- LEFT COLUMN --}}
<div class="space-y-5 sm:space-y-6">

    {{-- ================= CONTENT TAB ================= --}}
    <div id="bio-tab-content" data-bio-tab>

            
            {{-- FORM CARD --}}
            <div class="bg-white rounded-xl shadow-sm border p-4 sm:p-6 space-y-5 sm:space-y-6">
                
                {{-- Bio Page Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bio Page Name
                    </label>
                    <input id="bioName"
                           name="title"
                           value="{{ $editingBio->title }}"
                           oninput="updatePreview()"
                           class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                           placeholder="Enter bio page name">
                </div>

                {{-- Bio Page Alias --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bio Page Alias
                    </label>
                    <div class="flex flex-col sm:flex-row">
                        <span class="inline-flex items-center px-3 sm:px-4 py-3 bg-gray-50 border border-gray-300 rounded-t-lg sm:rounded-l-lg sm:rounded-tr-none sm:border-r-0 text-gray-600 text-xs sm:text-sm">
                            {{ bio_domain() }}/
                        </span>
                        <input name="alias"
                               value="{{ $editingBio->slug }}"
                               class="flex-1 px-3 sm:px-4 py-3 border border-gray-300 rounded-b-lg sm:rounded-r-lg sm:rounded-bl-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                               placeholder="your-alias">
                    </div>
                    <p class="mt-2 text-xs sm:text-sm text-gray-500">
                        Leave empty to generate a random alias
                    </p>
                </div>

                {{-- Add Content Button --}}
                <button type="button"
                        onclick="openAddContent()"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 sm:py-3.5 rounded-lg font-medium text-sm sm:text-base flex items-center justify-center gap-2 transition duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Link or Content
                </button>

                {{-- CONTENT BLOCKS CONTAINER --}}
                <div id="contentBlocks" class="space-y-4 mt-6">
                    @foreach($editingBio->links ?? [] as $i => $link)

                  @if($link['type'] === 'tagline')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">Tagline</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 block-body">
        <input type="text"
               name="links[{{ $i }}][text]"
               value="{{ $link['text'] }}"
               class="w-full px-3 py-2 border rounded-lg text-sm sm:text-base"
               oninput="updateTaglineLive(this.value)">
        <input type="hidden" name="links[{{ $i }}][type]" value="tagline">
    </div>
</div>
@endif

                   @if($link['type'] === 'link')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">Link</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    <div class="border-t p-4 space-y-3 block-body">
        <input type="text"
               name="links[{{ $i }}][text]"
               value="{{ $link['text'] }}"
               class="w-full px-3 py-2 border rounded-lg text-sm sm:text-base">

        <input type="url"
               name="links[{{ $i }}][url]"
               value="{{ $link['url'] }}"
               class="w-full px-3 py-2 border rounded-lg text-sm sm:text-base">

        <input type="hidden" name="links[{{ $i }}][type]" value="link">
    </div>
</div>
@endif


                   @if($link['type'] === 'heading')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">Heading</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    <div class="border-t p-4 space-y-3 block-body">
        <select name="links[{{ $i }}][style]" class="w-full px-3 py-2 border rounded-lg">
            @foreach(['h1','h2','h3','h4','h5','h6'] as $h)
            <option value="{{ $h }}" {{ ($link['style'] ?? 'h5') === $h ? 'selected' : '' }}>
                {{ strtoupper($h) }}
            </option>
            @endforeach
        </select>

        <input type="text"
               name="links[{{ $i }}][text]"
               value="{{ $link['text'] }}"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="color"
               name="links[{{ $i }}][color]"
               value="{{ $link['color'] ?? '#000000' }}">

        <input type="hidden" name="links[{{ $i }}][type]" value="heading">
    </div>
</div>
@endif

                    {{-- TEXT (QUILL) --}}
                   @if($link['type'] === 'text')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">Text</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    <div class="border-t p-4 space-y-3 block-body">
        <div id="editor-{{ $i }}" class="min-h-[150px]"></div>

        <input type="hidden"
               name="links[{{ $i }}][text]"
               id="input-{{ $i }}"
               value="{{ e($link['text']) }}">

        <input type="hidden" name="links[{{ $i }}][type]" value="text">
    </div>
</div>
<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const quill{{ $i }} = new Quill('#editor-{{ $i }}', {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline'],
                                        [{ list: 'ordered' }, { list: 'bullet' }],
                                        ['link'],
                                        ['clean']
                                    ]
                                }
                            });

                            quill{{ $i }}.root.innerHTML = `{!! addslashes($link['text']) !!}`;

                            quill{{ $i }}.on('text-change', function () {
                                document.getElementById('input-{{ $i }}').value =
                                    quill{{ $i }}.root.innerHTML;
                            });
                        });
                    </script>
                    @endif
{{-- DIVIDER --}}
@if($link['type'] === 'divider')
<div class="bg-white border rounded-xl shadow-sm">

    {{-- HEADER (CLICK TO TOGGLE) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleDivider(this)">
        <div class="flex items-center gap-2">
            <span class="text-lg"></span>
            <span class="font-medium text-sm sm:text-base">Divider</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (FIELDS) --}}
    <div class="border-t p-4 space-y-4 divider-body">

        {{-- STYLE --}}
        <div>
            <label class="text-xs sm:text-sm font-medium text-gray-700">Style</label>
            <select name="links[{{ $i }}][style]"
                    class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base">
                <option value="solid"  {{ ($link['style'] ?? '') === 'solid' ? 'selected' : '' }}>Solid</option>
                <option value="dashed" {{ ($link['style'] ?? '') === 'dashed' ? 'selected' : '' }}>Dashed</option>
                <option value="dotted" {{ ($link['style'] ?? '') === 'dotted' ? 'selected' : '' }}>Dotted</option>
            </select>
        </div>

        {{-- HEIGHT --}}
        <div>
            <label class="text-xs sm:text-sm font-medium text-gray-700">Height</label>
            <input type="range"
                   min="1" max="10"
                   value="{{ $link['height'] ?? 1 }}"
                   name="links[{{ $i }}][height]"
                   class="w-full">
        </div>

        {{-- COLOR --}}
        <div class="flex items-center gap-3">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Color</label>
            <input type="color"
                   name="links[{{ $i }}][color]"
                   value="{{ $link['color'] ?? '#000000' }}"
                   class="w-8 h-8 sm:w-10 sm:h-10 cursor-pointer">
        </div>

        <input type="hidden" name="links[{{ $i }}][type]" value="divider">
    </div>
</div>
@endif
@if($link['type'] === 'html')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <div class="flex items-center gap-2">
            <span class="font-medium text-sm sm:text-base">&lt;/&gt; HTML</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body">
        <textarea
            name="links[{{ $i }}][text]"
            rows="4"
            class="w-full px-3 py-2 border rounded-lg text-sm sm:text-base"
            placeholder="e.g. <div>Custom HTML</div>"
        >{{ $link['text'] ?? '' }}</textarea>

        <input type="hidden" name="links[{{ $i }}][type]" value="html">
    </div>
</div>
@endif
@if($link['type'] === 'image')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <div class="flex items-center gap-2">
            <span class="font-medium text-sm sm:text-base">🖼 Image</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-4 block-body">

        {{-- FILE --}}
        <div>
            <label class="text-xs sm:text-sm font-medium text-gray-700">Image File</label>
            <input type="file"
       name="links[{{ $i }}][file]"
       accept="image/*">

        </div>

        {{-- LINK --}}
        <div>
            <label class="text-xs sm:text-sm font-medium text-gray-700">
                Link (optional)
            </label>
            <input type="url"
                   name="links[{{ $i }}][url]"
                   value="{{ $link['url'] ?? '' }}"
                   class="w-full mt-1 px-3 py-2 border rounded-lg text-sm sm:text-base"
                   placeholder="https://example.com">
        </div>

        <input type="hidden" name="links[{{ $i }}][type]" value="image">
    </div>
</div>
@endif
@if($link['type'] === 'phone_call')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <div class="flex items-center gap-2">
            <span class="text-lg">📞</span>
            <span class="font-medium text-sm sm:text-base">Phone Call</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">
        <input type="text"
               name="links[{{ $i }}][phone]"
               value="{{ $link['phone'] ?? '' }}"
               placeholder="Phone number"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="text"
               name="links[{{ $i }}][label]"
               value="{{ $link['label'] ?? 'Call us' }}"
               placeholder="Button label"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden"
               name="links[{{ $i }}][type]"
               value="phone_call">
    </div>
</div>
@endif
@if($link['type'] === 'whatsapp_call')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <div class="flex items-center gap-2">
            <span class="text-lg">📞</span>
            <span class="font-medium text-sm sm:text-base">WhatsApp Call</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">
        <input type="text"
               name="links[{{ $i }}][phone]"
               value="{{ $link['phone'] ?? '' }}"
               placeholder="WhatsApp number"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="text"
               name="links[{{ $i }}][label]"
               value="{{ $link['label'] ?? 'Call on WhatsApp' }}"
               placeholder="Button label"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden"
               name="links[{{ $i }}][type]"
               value="whatsapp_call">
    </div>
</div>
@endif
@if($link['type'] === 'whatsapp_message')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <div class="flex items-center gap-2">
            <span class="text-lg">💬</span>
            <span class="font-medium text-sm sm:text-base">WhatsApp Message</span>
        </div>

        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">
        <input type="text"
               name="links[{{ $i }}][phone]"
               value="{{ $link['phone'] ?? '' }}"
               placeholder="WhatsApp number"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="text"
               name="links[{{ $i }}][label]"
               value="{{ $link['label'] ?? 'Message on WhatsApp' }}"
               placeholder="Button label"
               class="w-full px-3 py-2 border rounded-lg">

        <textarea
            name="links[{{ $i }}][message]"
            placeholder="Default message (optional)"
            class="w-full px-3 py-2 border rounded-lg">{{ $link['message'] ?? '' }}</textarea>

        <input type="hidden"
               name="links[{{ $i }}][type]"
               value="whatsapp_message">
    </div>
</div>
@endif

@if($link['type'] === 'video')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">🎥 Video</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 space-y-4 block-body hidden">

        {{-- preserve old file --}}
        <input type="hidden"
               name="links[{{ $i }}][file]"
               value="{{ $link['file'] ?? '' }}">

        {{-- upload new video --}}
        <input type="file"
               name="links[{{ $i }}][file]"
               accept="video/*"
               class="w-full">

        {{-- external link (optional) --}}
        <input type="url"
               name="links[{{ $i }}][url]"
               value="{{ $link['url'] ?? '' }}"
               placeholder="External link (optional)"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden" name="links[{{ $i }}][type]" value="video">
    </div>

</div>
@endif
@if($link['type'] === 'audio')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">🔊 Audio</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 space-y-4 block-body hidden">

        {{-- preserve old file --}}
        <input type="hidden"
               name="links[{{ $i }}][file]"
               value="{{ $link['file'] ?? '' }}">

        {{-- upload new audio --}}
        <input type="file"
               name="links[{{ $i }}][file]"
               accept="audio/*"
               class="w-full">

        <input type="hidden" name="links[{{ $i }}][type]" value="audio">
    </div>

</div>
@endif

@if($link['type'] === 'pdf')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">📄 PDF Document</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 space-y-3 block-body hidden">

        {{-- document title --}}
        <input type="text"
               name="links[{{ $i }}][title]"
               value="{{ $link['title'] ?? '' }}"
               placeholder="Document title"
               class="w-full px-3 py-2 border rounded-lg">

        {{-- upload new pdf --}}
        <input type="file"
               name="links[{{ $i }}][file]"
               accept="application/pdf"
               class="w-full">

        <input type="hidden" name="links[{{ $i }}][type]" value="pdf">
    </div>

</div>
@endif

@if($link['type'] === 'youtube')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">▶️ YouTube Video</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 block-body hidden">

        <input type="url"
               name="links[{{ $i }}][url]"
               value="{{ $link['url'] ?? '' }}"
               placeholder="https://youtube.com/watch?v=..."
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden" name="links[{{ $i }}][type]" value="youtube">
    </div>

</div>
@endif
@if($link['type'] === 'spotify')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">🎵 Spotify</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 block-body hidden">

        <input type="url"
               name="links[{{ $i }}][url]"
               value="{{ $link['url'] ?? '' }}"
               placeholder="https://open.spotify.com/..."
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden" name="links[{{ $i }}][type]" value="spotify">
    </div>

</div>
@endif
@if($link['type'] === 'instagram')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">📸 Instagram Post</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 block-body hidden">

        <input type="url"
               name="links[{{ $i }}][url]"
               value="{{ $link['url'] ?? '' }}"
               placeholder="https://www.instagram.com/p/..."
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden" name="links[{{ $i }}][type]" value="instagram">
    </div>

</div>
@endif
@if($link['type'] === 'maps')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER (click to toggle) --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">📍 Google Maps</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY (hidden by default) --}}
    <div class="border-t p-4 block-body hidden">

        <input type="text"
               name="links[{{ $i }}][address]"
               value="{{ $link['address'] ?? '' }}"
               placeholder="e.g. Apple Park, Cupertino"
               class="w-full px-3 py-2 border rounded-lg">

        <input type="hidden" name="links[{{ $i }}][type]" value="maps">
    </div>

</div>
@endif
@if(($link['type'] ?? '') === 'faq')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">❓ FAQ</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">

        <input type="text"
               name="links[{{ $i }}][question]"
               value="{{ $link['question'] ?? '' }}"
               placeholder="Question"
               class="w-full px-3 py-2 border rounded-lg text-sm">

        <textarea
            name="links[{{ $i }}][answer]"
            rows="3"
            placeholder="Answer"
            class="w-full px-3 py-2 border rounded-lg text-sm"
        >{{ $link['answer'] ?? '' }}</textarea>

        <input type="hidden" name="links[{{ $i }}][type]" value="faq">
        <input type="hidden" name="links[{{ $i }}][enabled]" value="1">
    </div>
</div>
@endif
@if(($link['type'] ?? '') === 'contact_form')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">📩 Contact Form</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">

        <input type="text"
               name="links[{{ $i }}][text]"
               value="{{ $link['text'] ?? '' }}"
               placeholder="Heading (e.g. Contact Me)"
               class="w-full px-3 py-2 border rounded-lg text-sm">

        <textarea
            name="links[{{ $i }}][disclaimer]"
            rows="2"
            placeholder="Disclaimer (optional)"
            class="w-full px-3 py-2 border rounded-lg text-sm"
        >{{ $link['disclaimer'] ?? '' }}</textarea>

        <input type="hidden" name="links[{{ $i }}][type]" value="contact_form">
        <input type="hidden" name="links[{{ $i }}][enabled]" value="1">
    </div>
</div>
@endif
@if(($link['type'] ?? '') === 'newsletter')
<div class="bg-white border rounded-xl shadow-sm block-wrapper">

    {{-- HEADER --}}
    <div class="flex items-center justify-between p-4 cursor-pointer"
         onclick="toggleBlock(this)">
        <span class="font-medium text-sm sm:text-base">📬 Newsletter</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform">
            <path d="M6 9l6 6 6-6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"/>
        </svg>
    </div>

    {{-- BODY --}}
    <div class="border-t p-4 space-y-3 block-body hidden">

        <input type="text"
               name="links[{{ $i }}][text]"
               value="{{ $link['text'] ?? '' }}"
               placeholder="Button text (e.g. Subscribe)"
               class="w-full px-3 py-2 border rounded-lg text-sm">

        <textarea
            name="links[{{ $i }}][description]"
            rows="2"
            placeholder="Description (optional)"
            class="w-full px-3 py-2 border rounded-lg text-sm"
        >{{ $link['description'] ?? '' }}</textarea>

        <textarea
            name="links[{{ $i }}][disclaimer]"
            rows="2"
            placeholder="Disclaimer (optional)"
            class="w-full px-3 py-2 border rounded-lg text-sm"
        >{{ $link['disclaimer'] ?? '' }}</textarea>

        <input type="hidden" name="links[{{ $i }}][type]" value="newsletter">
        <input type="hidden" name="links[{{ $i }}][enabled]" value="1">
    </div>
</div>
@endif


@endforeach
                </div>

                {{-- Save Button --}}
                <button type="submit"
                        class="w-full bg-black hover:bg-gray-900 text-white py-3 sm:py-3.5 rounded-lg font-medium text-sm sm:text-base transition duration-200 hover:shadow-md mt-6">
                    Save Changes
                </button>
            </div>
     
        </div>
                 {{-- 👇 YAHAN likhna hai --}}
    <div id="bio-tab-settings" data-bio-tab class="hidden">
        @include('bio.tabs.settings')
    </div>
    <div id="bio-tab-design" data-bio-tab class="hidden">
        @include('bio.tabs.design')
    </div>
    <div id="bio-tab-social-links" data-bio-tab class="hidden">
        @include('bio.tabs.social-links')
    </div>
       <div id="bio-tab-statistics" data-bio-tab class="hidden">
        @include('bio.tabs.statistics')
    </div>


</div>
        {{-- RIGHT COLUMN - MOBILE PREVIEW --}}
      {{-- RIGHT COLUMN - MOBILE PREVIEW --}}
<div id="livePreviewWrapper" class="sticky top-6">

            
            {{-- Preview Label --}}
            <div class="mb-4 text-center">
                <p class="text-sm font-medium text-gray-700">Live Preview</p>
                <p class="text-xs text-gray-500 mt-1">Updates in real-time</p>
            </div>
            
            {{-- Phone Frame --}}
            <div class="relative mx-auto max-w-[340px] sm:mx-0">
                {{-- Phone Top Speaker --}}
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 sm:w-32 h-4 sm:h-5 bg-black rounded-b-2xl z-10"></div>
                
                {{-- Phone Body --}}
                <div class="w-full max-w-[320px] sm:max-w-[340px] h-[600px] sm:h-[680px] bg-gradient-to-b from-gray-900 to-black rounded-[40px] sm:rounded-[48px] p-2 sm:p-3 shadow-2xl relative overflow-hidden mx-auto">
                    
                    {{-- Phone Notch --}}
                    <div class="absolute top-2 sm:top-3 left-1/2 transform -translate-x-1/2 w-24 sm:w-32 h-4 sm:h-5 bg-black rounded-b-xl z-20"></div>
                    
                    {{-- Phone Screen --}}
                    <div class="w-full h-full bg-white rounded-[32px] sm:rounded-[40px] overflow-y-auto p-3 sm:p-4 md:p-5 pt-10 sm:pt-12">
                        
                        {{-- Profile Avatar --}}
                        <div class="flex flex-col items-center mb-4 sm:mb-6">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 shadow">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            
                            {{-- Bio Name --}}
                            <h2 id="previewName" class="text-base sm:text-lg md:text-xl font-bold text-gray-900 text-center mb-1">
                                {{ $editingBio->title }}
                            </h2>
                            
                            {{-- Bio URL --}}
                            <p class="text-xs sm:text-sm text-gray-500 text-center">
                                {{ bio_domain() }}/{{ $editingBio->slug }}
                            </p>
                            
                            {{-- Tagline Preview --}}
                            <p id="previewTagline" class="text-xs sm:text-sm text-gray-600 text-center mt-2 hidden"></p>
                            {{-- SOCIAL ICONS LIVE PREVIEW --}}
                            <div id="livePreviewSocials"
                                class="flex justify-center gap-3 mt-4 flex-wrap">
                            </div>

                        </div>

                        {{-- Content Blocks Preview --}}
                        <div class="space-y-3 sm:space-y-4">
                            {{-- Default Button --}}
                            <div class="bg-purple-100 text-purple-700 py-3 rounded-xl text-center font-medium shadow-sm text-xs sm:text-sm md:text-base">
                                QRURL
                            </div>
                            
                            {{-- Content Blocks Preview --}}
                            <div class="space-y-3 sm:space-y-4">
                                @foreach($editingBio->links ?? [] as $block)

                                {{-- TAGLINE --}}
                                @if($block['type'] === 'tagline' && ($block['enabled'] ?? true))
                                <p class="text-xs sm:text-sm text-gray-600 text-center">
                                    {{ $block['text'] }}
                                </p>
                                @endif

                                {{-- HEADING --}}
                                @if($block['type'] === 'heading' && ($block['enabled'] ?? true))
                                @php
                                $tag = $block['style'] ?? 'h5';
                                @endphp

                                <{{ $tag }}
                                    class="text-center font-semibold"
                                    style="color: {{ $block['color'] ?? '#000' }}"
                                >
                                    {{ $block['text'] }}
                                </{{ $tag }}>
                                @endif

                                {{-- TEXT --}}
                                @if($block['type'] === 'text' && ($block['enabled'] ?? true))
                                <div class="text-xs sm:text-sm text-gray-700 text-center leading-relaxed">
                                    {!! $block['text'] !!}
                                </div>
                                @endif

                                {{-- LINK --}}
                                @if($block['type'] === 'link' && ($block['enabled'] ?? true))
                                <a href="{{ $block['url'] }}"
                                   target="_blank"
                                   class="block bg-purple-100 text-purple-700 py-3 rounded-xl text-center font-medium text-xs sm:text-sm md:text-base">
                                    {{ $block['text'] }}
                                </a>
                                @endif
                                {{-- DIVIDER --}}
                                @if($block['type'] === 'divider' && ($block['enabled'] ?? true))
                                    <hr
                                        class="my-3 sm:my-4"
                                        style="
                                            border-top: {{ $block['height'] ?? 1 }}px {{ $block['style'] ?? 'solid' }} {{ $block['color'] ?? '#000' }};
                                        "
                                    >
                                @endif
@if(($block['type'] ?? '') === 'html' && ($block['enabled'] ?? true))
                                    <div class="text-xs sm:text-sm text-gray-700 text-center leading-relaxed">
                                        {!! $block['text'] !!}
                                    </div>
                                @endif
                              @if(($block['type'] ?? '') === 'image')
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/' . $block['file']) }}"
                                        class="max-w-full rounded-xl"
                                        alt="">
                                </div>
                            @endif
                            {{-- PHONE CALL PREVIEW --}}
                            @if(($block['type'] ?? '') === 'phone_call' && ($block['enabled'] ?? true))
<a href="tel:{{ $block['phone'] }}"
   class="flex items-center justify-center gap-2
          bg-purple-100 text-purple-700
          py-3 rounded-xl font-medium text-sm">
    📞 {{ $block['label'] ?? 'Call' }}
</a>
@endif

                           @if(($block['type'] ?? '') === 'whatsapp_call' && ($block['enabled'] ?? true))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $block['phone']) }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-green-100 text-green-700
          py-3 rounded-xl font-medium text-sm">
    📞 {{ $block['label'] ?? 'WhatsApp Call' }}
</a>
@endif

@if(($block['type'] ?? '') === 'whatsapp_message' && ($block['enabled'] ?? true))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $block['phone']) }}?text={{ urlencode($block['message'] ?? '') }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-green-50 text-green-700
          py-3 rounded-xl font-medium text-sm">
    💬 {{ $block['label'] ?? 'WhatsApp Message' }}
</a>
@endif
@if(($block['type'] ?? '') === 'video' && !empty($block['file']))
<video controls class="w-full rounded-xl shadow-sm">
    <source src="{{ asset('storage/' . $block['file']) }}" type="video/mp4">
    Your browser does not support the video tag.
</video>
@endif

@if(($block['type'] ?? '') === 'audio' && !empty($block['file']))
<audio controls class="w-full">
    <source src="{{ asset('storage/' . $block['file']) }}">
    Your browser does not support the audio element.
</audio>
@endif
@if($block['type'] === 'pdf' && !empty($block['file']))
<a href="{{ asset('storage/'.$block['file']) }}"
   target="_blank"
   class="flex items-center justify-between gap-3 bg-gray-100 px-4 py-3 rounded-xl text-sm font-medium">
    📄 {{ $block['title'] ?? 'View PDF' }}
</a>
@endif
@if(
    ($block['type'] ?? '') === 'youtube' &&
    ($block['enabled'] ?? true) &&
    !empty($block['url'])
)
@php
    $url = trim($block['url']);

    preg_match(
        '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|shorts\/|embed\/))([^&\/\?]+)/',
        $url,
        $m
    );

    $videoId = $m[1] ?? null;
@endphp

@if($videoId)
<iframe
    class="w-full aspect-video rounded-xl"
    src="https://www.youtube.com/embed/{{ $videoId }}"
    frameborder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
    allowfullscreen>
</iframe>
@endif
@endif

@if($block['type'] === 'spotify' && !empty($block['url']))
<iframe
    class="w-full rounded-xl"
    style="height: 80px"
    src="https://open.spotify.com/embed/{{ Str::after($block['url'], 'open.spotify.com/') }}"
    frameborder="0"
    allow="encrypted-media">
</iframe>
@endif
@if(($block['type'] ?? '') === 'instagram' && ($block['enabled'] ?? true))
<a href="{{ $block['url'] }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-pink-100 text-pink-700
          py-3 rounded-xl font-medium text-sm">
    📸 View Instagram Post
</a>
@endif
@if(($block['type'] ?? '') === 'maps' && ($block['enabled'] ?? true))
<a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($block['address']) }}"
   target="_blank"
   class="flex items-center justify-center gap-2
          bg-blue-100 text-blue-700
          py-3 rounded-xl font-medium text-sm">
    📍 {{ $block['address'] }}
</a>
@endif
@if(($block['type'] ?? '') === 'faq' && ($block['enabled'] ?? true))
<div class="bg-gray-50 border rounded-xl p-4 text-left">
    <p class="font-medium text-sm text-gray-900">
        ❓ {{ $block['question'] }}
    </p>

    <p class="text-sm text-gray-600 mt-2">
        {{ $block['answer'] }}
    </p>
</div>
@endif
@if(($block['type'] ?? '') === 'contact_form')
<div class="bg-gray-100 rounded-xl p-4 text-left opacity-90">
    <p class="font-medium text-sm">📩 Contact Form</p>

    <input disabled class="w-full mt-2 px-3 py-2 border rounded-lg text-xs"
           placeholder="Email">

    <div class="w-full mt-2 px-3 py-2 border rounded-lg text-xs text-gray-400">
        Message…
    </div>

    <div class="mt-2 bg-purple-200 text-purple-700 py-2 rounded-lg text-xs text-center">
        Send
    </div>
</div>
@endif
@if(($block['type'] ?? '') === 'newsletter')
<div class="bg-gray-100 rounded-xl p-4 text-center opacity-90">
    <p class="font-medium text-sm">📬 Newsletter</p>

    <input disabled class="w-full mt-2 px-3 py-2 border rounded-lg text-xs"
           placeholder="Email">

    <div class="mt-2 bg-purple-200 text-purple-700 py-2 rounded-lg text-xs">
        Subscribe
    </div>
</div>
@endif




                                @endforeach
                            </div>
                            
                            {{-- Default Content --}}
                            <div class="bg-gray-100 text-gray-700 py-3 rounded-xl text-center font-medium shadow-sm text-xs sm:text-sm md:text-base">
                                More content ↓
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-100 text-center">
                            <p class="text-xs text-gray-400">
                                Powered by {{ parse_url(bio_domain(), PHP_URL_HOST) }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Home Button --}}
                    <div class="absolute bottom-3 sm:bottom-4 left-1/2 transform -translate-x-1/2 w-20 sm:w-24 h-1 sm:h-1.5 bg-gray-800 rounded-full"></div>
                </div>
            </div>
        </div>
   </form>
</div>
<script>
// Live Preview में Settings Tab के बदलाव दिखाने के लिए
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Sensitive Content Warning
    window.addEventListener('showSensitiveWarning', function(event) {
        if (event.detail.show) {
            // Create sensitive warning overlay
            const warningOverlay = document.createElement('div');
            warningOverlay.id = 'sensitiveWarningOverlay';
            warningOverlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.9);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            `;
            
            warningOverlay.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 15px; max-width: 400px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">⚠️</div>
                    <h3 style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Sensitive Content</h3>
                    <p style="color: #666; margin-bottom: 20px; line-height: 1.5;">
                        This page contains sensitive content which may not be suitable for all ages. 
                        By continuing, you agree to our terms of service.
                    </p>
                    <button onclick="document.getElementById('sensitiveWarningOverlay').remove()" 
                            style="background: #7c3aed; color: white; padding: 12px 30px; border: none; 
                                   border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%;">
                        Continue
                    </button>
                </div>
            `;
            
            document.body.appendChild(warningOverlay);
        }
    });
    
    // 2. Meta Info Update
    window.addEventListener('updateMetaInfo', function(event) {
        const { metaTitle, metaDescription } = event.detail;
        
        // Update preview title if meta title exists
        const previewName = document.getElementById('previewName');
        if (metaTitle && previewName) {
            previewName.textContent = metaTitle;
        }
        
        // Show meta description in preview
        const previewContainer = document.querySelector('.w-full.h-full.bg-white');
        if (previewContainer) {
            // Find or create meta description element
            let metaDescEl = document.getElementById('previewMetaDescription');
            if (!metaDescEl) {
                metaDescEl = document.createElement('p');
                metaDescEl.id = 'previewMetaDescription';
                metaDescEl.className = 'text-xs sm:text-sm text-gray-600 text-center mt-2';
                const profileDiv = document.querySelector('.flex.flex-col.items-center.mb-4');
                if (profileDiv) {
                    profileDiv.appendChild(metaDescEl);
                }
            }
            
            if (metaDescription) {
                metaDescEl.textContent = metaDescription;
                metaDescEl.classList.remove('hidden');
            } else {
                metaDescEl.classList.add('hidden');
            }
        }
    });
    
    // 3. General Settings Update
    window.addEventListener('settingsUpdated', function(event) {
        const { setting, value } = event.detail;
        console.log('🎯 Live Preview received:', setting, '=', value);
        
        // Handle different settings
        if (setting === 'sensitive' && value) {
            // Already handled by showSensitiveWarning event
        }
        
        if (setting === 'remove_branding') {
            const footer = document.querySelector('.mt-6.pt-4.border-t.border-gray-100.text-center');
            if (footer) {
                footer.style.display = value ? 'none' : 'block';
            }
        }
        
        if (setting === 'show_avatar') {
            const avatar = document.querySelector('.w-16.h-16.sm\\:w-20.sm\\:h-20.md\\:w-24.md\\:h-24');
            if (avatar) {
                avatar.style.display = value ? 'flex' : 'none';
            }
        }
        
        if (setting === 'avatar_style') {
            const avatar = document.querySelector('.w-16.h-16.sm\\:w-20.sm\\:h-20.md\\:w-24.md\\:h-24');
            if (avatar) {
                // Remove all border radius classes
                avatar.classList.remove('rounded-2xl', 'rounded-full', 'rounded-none');
                
                // Add new style
                if (value === 'circle') {
                    avatar.classList.add('rounded-full');
                } else if (value === 'square') {
                    avatar.classList.add('rounded-none');
                } else {
                    avatar.classList.add('rounded-2xl');
                }
            }
        }
    });
    
    // 4. Branding Update
    window.addEventListener('updateBranding', function(event) {
        const { removeBranding } = event.detail;
        const footer = document.querySelector('.mt-6.pt-4.border-t.border-gray-100.text-center');
        if (footer) {
            footer.style.display = removeBranding ? 'none' : 'block';
        }
    });
    
    // 5. Avatar Update
    window.addEventListener('updateAvatar', function(event) {
        const { showAvatar, avatarStyle } = event.detail;
        
        const avatar = document.querySelector('.w-16.h-16.sm\\:w-20.sm\\:h-20.md\\:w-24.md\\:h-24');
        if (avatar) {
            // Show/hide avatar
            avatar.style.display = showAvatar ? 'flex' : 'none';
            
            // Update style
            avatar.classList.remove('rounded-2xl', 'rounded-full', 'rounded-none');
            
            if (avatarStyle === 'circle') {
                avatar.classList.add('rounded-full');
            } else if (avatarStyle === 'square') {
                avatar.classList.add('rounded-none');
            } else {
                avatar.classList.add('rounded-2xl');
            }
        }
    });
    
    // Load initial settings from localStorage
    setTimeout(() => {
        const metaTitle = localStorage.getItem('bio_setting_meta_title');
        const metaDesc = localStorage.getItem('bio_setting_meta_description');
        const sensitive = localStorage.getItem('bio_setting_sensitive');
        const showAvatar = localStorage.getItem('bio_setting_show_avatar');
        const avatarStyle = localStorage.getItem('bio_setting_avatar_style');
        const removeBranding = localStorage.getItem('bio_setting_remove_branding');
        
        // Apply meta title
        if (metaTitle) {
            document.title = metaTitle + ' | QRURL';
            const previewName = document.getElementById('previewName');
            if (previewName) previewName.textContent = metaTitle;
        }
        
        // Apply sensitive warning
        if (sensitive === 'true') {
            window.dispatchEvent(new CustomEvent('showSensitiveWarning', {
                detail: { show: true }
            }));
        }
        
        // Apply avatar settings
        if (showAvatar === 'false') {
            const avatar = document.querySelector('.w-16.h-16.sm\\:w-20.sm\\:h-20.md\\:w-24.md\\:h-24');
            if (avatar) avatar.style.display = 'none';
        }
        
        if (avatarStyle) {
            const avatar = document.querySelector('.w-16.h-16.sm\\:w-20.sm\\:h-20.md\\:w-24.md\\:h-24');
            if (avatar) {
                avatar.classList.remove('rounded-2xl', 'rounded-full', 'rounded-none');
                if (avatarStyle === 'circle') {
                    avatar.classList.add('rounded-full');
                } else if (avatarStyle === 'square') {
                    avatar.classList.add('rounded-none');
                } else {
                    avatar.classList.add('rounded-2xl');
                }
            }
        }
        
        // Apply branding
        if (removeBranding === 'true') {
            const footer = document.querySelector('.mt-6.pt-4.border-t.border-gray-100.text-center');
            if (footer) footer.style.display = 'none';
        }
        
        console.log('✅ Live Preview initialized with saved settings');
    }, 500);
    
});
</script>
<script>
/* ================= CONTENT LIVE PREVIEW ================= */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.content-input').forEach(el => {
        // DESIGN TAB KE INPUTS KO COMPLETELY SKIP KARO
        if (el.closest('#design-tab-container') || el.classList.contains('design-input')) {
            console.log('⏭️ Skipping design input from content handler:', el.name);
            return; // Skip completely
        }
        
        el.addEventListener('input', fireContent);
        el.addEventListener('change', fireContent);
    });

    function fireContent(e) {
        const el = e.target;
        if (!el.name) return;

        const key = el.name;
        const value = el.type === 'checkbox' ? el.checked : el.value;

        window.dispatchEvent(new CustomEvent('contentUpdated', {
            detail: { key: key, value: value }
        }));

        console.log('📝 CONTENT UPDATE:', key, value);
    }
});
</script>
<script>
document.addEventListener('bio-preview-update', function () {
    if (window.updateBioPreview) {
        window.updateBioPreview();
    }
});
</script>

<script>
function renderSocialInPreview(platform, url) {
    const container = document.getElementById('livePreviewSocials');
    if (!container || !platformData[platform]) return;

    // already exists → update url only
    let existing = container.querySelector(`[data-platform="${platform}"]`);
    if (existing) {
        existing.href = url;
        return;
    }

    const config = platformData[platform];

    const a = document.createElement('a');
    a.href = url;
    a.target = "_blank";
    a.dataset.platform = platform;
    a.className = `
        w-11 h-11 rounded-full flex items-center justify-center
        shadow-sm hover:scale-105 transition
    `;
    a.style.backgroundColor = config.color + '20';
    a.style.border = `1px solid ${config.color}40`;

    // icon
    if (config.icon === 'link' || config.icon === 'globe') {
        a.innerHTML = `
            <svg class="w-5 h-5" fill="currentColor" style="color:${config.color}" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 100-16z"/>
            </svg>
        `;
    } else {
        a.innerHTML = `<i class="fab fa-${config.icon} text-lg" style="color:${config.color}"></i>`;
    }

    container.appendChild(a);
}
</script>
