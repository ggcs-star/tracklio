<?php

namespace App\Services\Bio;

use Illuminate\Http\Request;
use App\Models\BioPage;
use App\Models\BioPageView;
use Illuminate\Support\Facades\Log;

class BioIndexService
{
    public function handle(Request $request)
    {
        try {

            $biosQuery = BioPage::where('user_id', auth()->id());

            if ($request->filled('search')) {
                $search = $request->search;
                $biosQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            $bios = $biosQuery->latest()->get()->map(function ($bio) {
                $bio->views = BioPageView::where(
                    'bio_page_id',
                    (string) $bio->_id
                )->count();
                return $bio;
            });

            $editingBio = null;
            $stats = [];

            if ($request->filled('edit')) {
                $editingBio = BioPage::where('_id', $request->edit)
                    ->where('user_id', auth()->id())
                    ->first();

                if ($editingBio) {
                    $stats = app(BioStatsService::class)
                        ->build((string) $editingBio->_id);
                }
            }

            $createMode = $request->boolean('create');

            return view('bio.index', compact(
                'bios',
                'editingBio',
                'createMode',
                'stats'
            ));

        } catch (\Throwable $e) {
            Log::error('Bio index error', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }
}
