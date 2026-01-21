<?php

namespace App\Services\Bio;

use Illuminate\Http\Request;
use App\Models\BioPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Helpers\BioLinkBuilder;

class BioSaveService
{
    public function handle(Request $request)
    {
        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'alias' => [
                    'nullable',
                    'string',
                    'max:100',
                    'regex:/^[a-zA-Z0-9-]+$/',
                    function ($attribute, $value, $fail) use ($request) {
                        $slug = Str::slug($value);

                        $exists = BioPage::where('slug', $slug)
                            ->when($request->filled('id'), function ($q) use ($request) {
                                $q->where('_id', '!=', $request->id);
                            })
                            ->exists();

                        if ($exists) {
                            $fail('This bio page alias is already taken. Please choose another one.');
                        }
                    }
                ],
            ]);

            if (!$request->filled('id')) {
                $bio = new BioPage();
                $bio->user_id = auth()->id();
                $bio->is_active = true;

                $bio->slug = $request->filled('alias')
                    ? Str::slug($request->alias)
                    : Str::random(8);

                $bio->links = null;
                $bio->settings = null;
                $bio->design = null;
                $bio->social_links = null;
                $bio->social_display_style = null;
            } else {
                $bio = BioPage::where('_id', $request->id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
            }

            $bio->title = $request->title;

            if ($request->filled('alias') && $request->alias !== $bio->slug) {
                $bio->slug = Str::slug($request->alias);
            }

            $bio->links = BioLinkBuilder::build($request);

            if ($request->filled('design_json')) {
                $decodedDesign = json_decode($request->design_json, true);
                if (is_array($decodedDesign)) {
                    $request->merge(['design' => $decodedDesign]);
                }
            }

            if ($request->filled('id')) {
                $bio->settings = is_string($request->settings)
                    ? json_decode($request->settings, true) ?? []
                    : ($request->settings ?? []);

                $bio->design = is_string($request->design)
                    ? json_decode($request->design, true) ?? []
                    : ($request->design ?? []);

                $bio->social_links = is_string($request->social_links)
                    ? json_decode($request->social_links, true) ?? ['items' => []]
                    : ($request->social_links ?? ['items' => []]);
            }

            $bio->is_active = true;
            $bio->save();

            return redirect()
                ->route('bio.edit', $bio->_id)
                ->with('success', 'Saved successfully')
                ->with('reset_preview', true);

        } catch (\Throwable $e) {
            Log::error('Bio save error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        BioPage::where('_id', $request->id)
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()
            ->route('bio.index')
            ->with('success', 'Bio page deleted!');
    }
}
