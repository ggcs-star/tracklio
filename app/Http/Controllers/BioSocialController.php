<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BioPage;
use Illuminate\Support\Facades\Log;

class BioSocialController extends Controller
{
   public function add(Request $request, $id)
{
    try {
        Log::info('Social ADD hit', $request->all());

        $bio = BioPage::where('_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $socials = config('socials');
        $platform = $request->input('platform');
        $url = $request->input('url');
        $customData = $request->input('custom_data'); 

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['message' => 'Please enter a valid URL'], 422);
        }
        $items = collect($bio->social_links['items'] ?? [])
            ->reject(fn ($i) => $i['platform'] === $platform)
            ->values();
        if (str_starts_with($platform, 'custom_')) {
            if (!$customData || !is_array($customData) || !isset($customData['name']) || !isset($customData['icon'])) {
                return response()->json(['message' => 'Custom platform data missing'], 422);
            }

            $items->push([
                'platform' => $platform,
                'label' => $customData['name'],
                'icon' => $customData['icon'],
                'color' => $customData['color'] ?? '#6B7280',
                'url' => $url,
                'enabled' => true,
                'order' => $items->count() + 1,
                'custom_data' => $customData, 
                'is_custom' => true,
            ]);
        } else {
            if (!isset($socials[$platform])) {
                return response()->json(['message' => 'Invalid platform'], 422);
            }

            $items->push([
                'platform' => $platform,
                'label' => $socials[$platform]['label'],
                'icon' => $socials[$platform]['icon'],
                'color' => $socials[$platform]['color'] ?? '#6B7280',
                'url' => $url,
                'enabled' => true,
                'order' => $items->count() + 1,
                'is_custom' => false,
            ]);
        }

        $bio->social_links = [
            'position' => $bio->social_links['position'] ?? 'top',
            'items' => $items->toArray(),
        ];

        $bio->save();

        Log::info('Social saved', $bio->social_links);

        return response()->json(['ok' => true, 'message' => 'Link added successfully']);

    } catch (\Throwable $e) {
        Log::error('Social ADD error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
    }
}

    public function delete(Request $request, $id)
    {
        try {
            $bio = BioPage::where('_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $platform = $request->platform;

            $socialLinks = is_array($bio->social_links)
    ? $bio->social_links
    : ['items' => [], 'position' => 'top'];
            $socialLinks['items'] = collect($socialLinks['items'])
                ->reject(fn ($item) => $item['platform'] === $platform)
                ->values()
                ->toArray();

            $bio->social_links = $socialLinks;
            $bio->save();

            return response()->json(['ok' => true, 'message' => 'Link deleted successfully']);

        } catch (\Throwable $e) {
            Log::error('Social DELETE error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function reorder(Request $request, $id)
    {
        try {
            $bio = BioPage::where('_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $order = $request->input('order', []);
            
            if (empty($order)) {
                return response()->json(['success' => false, 'message' => 'No order provided']);
            }

            $socialLinks = $bio->social_links ?? ['items' => []];
            $items = collect($socialLinks['items']);
            $orderedItems = collect($order)
                ->map(function ($platform) use ($items) {
                    return $items->firstWhere('platform', $platform);
                })
                ->filter() 
                ->values()
                ->map(function ($item, $index) {
                    $item['order'] = $index + 1;
                    return $item;
                })
                ->toArray();
            $remainingItems = $items
                ->reject(fn ($item) => in_array($item['platform'], $order))
                ->values()
                ->map(function ($item, $index) use ($orderedItems) {
                    $item['order'] = count($orderedItems) + $index + 1;
                    return $item;
                })
                ->toArray();

            $socialLinks['items'] = array_merge($orderedItems, $remainingItems);
            $bio->social_links = $socialLinks;
            $bio->save();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('Social REORDER error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    public function saveDisplay(Request $request, $id)
    {
        try {
            $bio = BioPage::where('_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $position = $request->input('position', 'top');
            $style = $request->input('style', 'icon_only');
            $socialLinks = $bio->social_links ?? ['items' => [], 'position' => 'top'];

            $socialLinks['position'] = $position;
            $bio->social_display_style = $style;
            $bio->social_links = $socialLinks;
            $bio->save();

            return response()->json([
                'success' => true,
                'message' => 'Display settings saved successfully',
                'data' => [
                    'position' => $position,
                    'style' => $style
                ]
            ]);

        } catch (\Throwable $e) {
            Log::error('Social Display Save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Request $request, $id)
    {
        try {
            $bio = BioPage::where('_id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $platform = $request->input('platform');
            $enabled = $request->input('enabled');
            $socialLinks = $bio->social_links ?? ['items' => []];
            $socialLinks['items'] = collect($socialLinks['items'])
                ->map(function ($item) use ($platform, $enabled) {
                    if ($item['platform'] === $platform) {
                        $item['enabled'] = $enabled;
                    }
                    return $item;
                })
                ->toArray();

            $bio->social_links = $socialLinks;
            $bio->save();

            return response()->json([
                'ok' => true,
                'message' => 'Link updated successfully',
                'enabled' => $enabled
            ]);

        } catch (\Throwable $e) {
            Log::error('Social TOGGLE error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Server error'], 500);
        }
    }
}