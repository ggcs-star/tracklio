<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Calendar\CalendarService;

class CalendarController extends Controller
{
    public function index(
        Request $request,
        CalendarService $calendarService
    ) {

        return view(
            'calendar.index',
            $calendarService->getCalendarData($request)
        );
    }
}