<?php

namespace App\Services\Calendar;

use App\Models\Post;
use Carbon\Carbon;

class CalendarService
{
    public function getCalendarData($request): array
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $currentDate = Carbon::create($year, $month, 1);
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $posts = Post::query()
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('scheduled_at', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('published_at', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })
            ->latest()
            ->get();
        $posts->each(function ($post) {
            $post->formatted_content = \App\Helpers\TextFormatter::convert($post->content);
        });

        return [
            'posts' => $posts,
            'month' => $month,
            'year' => $year,
            'currentDate' => $currentDate,
            'daysInMonth' => $currentDate->daysInMonth,
            'startDay' => $currentDate->copy()->startOfMonth()->dayOfWeek,
        ];
    }
}