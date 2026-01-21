<?php

namespace App\Services\Bio;

use App\Models\BioPageView;

class BioStatsService
{
    public function build(string $bioId): array
    {
        $base = BioPageView::where('bio_page_id', $bioId);

        $totalClicks = $base->count();
        $uniqueClicks = $base->distinct('ip')->count('ip');

        $topCountry = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                ['$group' => ['_id' => '$country', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]],
                ['$limit' => 1],
            ]);
        })->first();

        $topCity = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                ['$group' => ['_id' => '$city', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]],
                ['$limit' => 1],
            ]);
        })->first();

        $locations = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                [
                    '$group' => [
                        '_id' => [
                            'country' => '$country',
                            'city' => '$city',
                        ],
                        'total' => ['$sum' => 1],
                    ],
                ],
                ['$sort' => ['total' => -1]],
            ]);
        });

        $devices = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                ['$group' => ['_id' => '$device', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]],
            ]);
        });

        $browsers = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                ['$group' => ['_id' => '$browser', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]],
            ]);
        });

        $referrers = BioPageView::raw(function ($collection) use ($bioId) {
            return $collection->aggregate([
                ['$match' => ['bio_page_id' => $bioId]],
                ['$group' => ['_id' => '$referrer', 'total' => ['$sum' => 1]]],
                ['$sort' => ['total' => -1]],
            ]);
        });

        return [
            'total_clicks' => $totalClicks,
            'unique_clicks' => $uniqueClicks,
            'top_country' => $topCountry->_id ?? 'Unknown',
            'top_city' => $topCity->_id ?? 'Unknown',

            'locations' => collect($locations)->map(function ($row) {
                return (object) [
                    'country' => $row->_id->country ?? 'Unknown',
                    'city' => $row->_id->city ?? '—',
                    'total' => $row->total,
                ];
            }),

            'devices' => collect($devices)->map(fn ($r) => (object) [
                'device' => $r->_id ?? 'Unknown',
                'total' => $r->total,
            ]),

            'browsers' => collect($browsers)->map(fn ($r) => (object) [
                'browser' => $r->_id ?? 'Unknown',
                'total' => $r->total,
            ]),

            'referrers' => collect($referrers)->map(fn ($r) => (object) [
                'referrer' => $r->_id,
                'total' => $r->total,
            ]),
        ];
    }
}
