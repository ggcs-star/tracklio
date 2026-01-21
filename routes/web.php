<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\{
    DashboardController,
    SocialAccountController,
    PostController,
    AuthController,
    QrLinkController,
    StatisticsController,
    ShortLinkController,
    ContactsController,
    WhatsAppAccountController,
    WhatsAppBulkController,
    WhatsAppCampaignController,
    QrBuilderController,
    BioPageController,
    BioSocialController,
    MongoPasswordResetController,
    ProfileController,
    NotificationController,
    BroadcastGroupController
};

use App\Models\QrLink;

/*
|--------------------------------------------------------------------------
| QR IMAGE (SVG SERVE) – IMPORTANT
|--------------------------------------------------------------------------
*/
Route::get('/qr-image/{code}', function ($code) {

    $qr = QrLink::where('short_code', $code)->firstOrFail();

    $path = 'qrcodes/qr_' . $code . '.svg';

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'QR SVG not found');
    }

    return Response::make(
        Storage::disk('public')->get($path),
        200,
        [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-cache'
        ]
    );
})->name('qr.image');


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('landing'))->name('landing');

/* Short link redirect */
Route::get('/s/{code}', [ShortLinkController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]+');

/* QR LINK REDIRECT (QrLinkController) */
Route::get('/q/{code}', [QrLinkController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9]+');

/* Auth */
Route::get('/login', [AuthController::class, 'showLoginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegisterPage'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/forgot-password', [MongoPasswordResetController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [MongoPasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [MongoPasswordResetController::class, 'resetPassword'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
   /*
|--------------------------------------------------------------------------
| BIO PAGES (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
   Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

   
   Route::post('/profile/avatar/remove', [ProfileController::class, 'removeAvatar'])
     ->name('profile.avatar.remove');

    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');   
// latest 10 (drawer default)
Route::get('/notifications', [NotificationController::class, 'latest']);

// all notifications (for "view all" INSIDE drawer)
Route::get('/notifications/all', [NotificationController::class, 'all']);

Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::post('/notifications/mark-read', [NotificationController::class, 'markAllRead']);
Route::post('/notifications/{id}/toggle', [NotificationController::class, 'toggleRead']);
Route::delete('/notifications-clear-all', [NotificationController::class, 'clearAll']);


  // Bio dashboard
Route::get('/bio', [BioPageController::class, 'index'])
    ->name('bio.index');

// Create OR Update (POST only)
Route::post('/bio/save', [BioPageController::class, 'save'])
    ->name('bio.save');
Route::get('/bio/{id}/edit', [BioPageController::class, 'edit'])->name('bio.edit');
// Delete
Route::post('/bio/delete', [BioPageController::class, 'delete'])
    ->name('bio.delete');

Route::post(
        '/bio/{id}/social/add',
        [BioSocialController::class, 'add']
    )->name('bio.social.add');

    Route::post(
        '/bio/{id}/social/delete',
        [BioSocialController::class, 'delete']
    )->name('bio.social.delete');
  Route::post('bio/social/reorder', [BioSocialController::class, 'reorder']) // NEW
        ->name('bio.social.reorder');
    Route::post(
        '/bio/{id}/social/toggle',
        [BioSocialController::class, 'toggle']
    )->name('bio.social.toggle');

  Route::post('bio/social/display/save', [BioSocialController::class, 'saveDisplay'])->name('bio.social.display.save');

});

/*

    /* Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live', [DashboardController::class, 'live'])->name('dashboard.live');

    /* Posts */
    Route::get('/create-post', [PostController::class, 'create'])->name('posts.create');
    Route::post('/create-post', [PostController::class, 'store'])->name('posts.store');

    /* Social Accounts */
    Route::get('/accounts', [SocialAccountController::class, 'index'])->name('accounts');

    Route::get('/accounts/facebook/connect', [SocialAccountController::class, 'connectFacebook'])->name('facebook.connect');
    Route::get('/accounts/facebook/callback', [SocialAccountController::class, 'facebookCallback'])->name('facebook.callback');

    Route::post('/accounts/instagram/connect', [SocialAccountController::class, 'connectInstagram'])->name('instagram.connect');

    Route::get('/youtube/connect', [SocialAccountController::class, 'connectYouTube'])->name('youtube.connect');
    Route::get('/youtube/callback', [SocialAccountController::class, 'youtubeCallback'])->name('youtube.callback');

    Route::post('/accounts/disconnect', [SocialAccountController::class, 'disconnect'])->name('accounts.disconnect');

    /* Statistics */
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

    /* Short Links */
    Route::get('/short-links', [ShortLinkController::class, 'index'])->name('short-links.index');
    Route::post('/short-links', [ShortLinkController::class, 'store']);
    Route::post('/short-links/{id}/update', [ShortLinkController::class, 'update'])->name('short-links.update');
    Route::delete('/short-links/{id}', [ShortLinkController::class, 'destroy'])->name('short-links.destroy');
    Route::get('/short-links/analytics', [ShortLinkController::class, 'analytics'])->name('short-links.analytics');

    /* QR LINKS (QrLinkController) */
    Route::get('/qr-links', [QrLinkController::class, 'index'])->name('qr-links.index');
    Route::post('/qr-links', [QrLinkController::class, 'store'])->name('qr-links.store');
    Route::post('/qr-links/{id}/update', [QrLinkController::class, 'update'])->name('qr-links.update');
    Route::delete('/qr-links/{id}', [QrLinkController::class, 'destroy'])->name('qr-links.destroy');
    Route::get('/qr-links/{id}', [QrLinkController::class, 'show'])->name('qr-links.show');

    /* Contacts */
      Route::get('/contacts', [ContactsController::class, 'index']);
    Route::post('/contacts', [ContactsController::class, 'store']);
    Route::post('/contacts/upload-csv', [ContactsController::class, 'uploadCsv']);
    Route::get('/contacts-page', function () {
        return view('contacts.index');
    });

Route::get('/whatsapp-accounts', [WhatsAppAccountController::class, 'page'])
    ->name('whatsapp.accounts');
Route::get('/whatsapp-accounts/data', [WhatsAppAccountController::class, 'index']);
Route::post('/whatsapp-accounts', [WhatsAppAccountController::class, 'store']);
Route::delete('/whatsapp-accounts/{id}', [WhatsAppAccountController::class, 'destroy']);

    Route::post('/whatsapp/bulk/prepare', [WhatsAppBulkController::class, 'prepare']);
    Route::post('/whatsapp/bulk/execute', [WhatsAppBulkController::class, 'execute']);

    Route::get('/whatsapp-campaigns', [WhatsAppCampaignController::class, 'index'])->name('whatsapp.campaigns');
    Route::post('/whatsapp-campaigns', [WhatsAppCampaignController::class, 'store']);
    Route::post('/whatsapp-campaigns/send', [WhatsAppCampaignController::class, 'send']);
  Route::get('/broadcast-groups', [BroadcastGroupController::class, 'index']);

    Route::post('/broadcast-groups', [BroadcastGroupController::class, 'store']);
    Route::put('/broadcast-groups/{id}', [BroadcastGroupController::class, 'update']);
    Route::delete('/broadcast-groups/{id}', [BroadcastGroupController::class, 'destroy']);
Route::get('/whatsapp-broadcasts', [BroadcastGroupController::class, 'index']);

    Route::delete(
        '/broadcast-groups/{groupId}/contacts/{contactId}',
        [BroadcastGroupController::class, 'removeContact']
    );
    Route::get('/whatsapp-broadcasts', function () {
    return view('brodcast.index');
})->middleware('auth');
Route::get('/api/contacts', function () {
    return \App\Models\Contact::where('user_id', (string) auth()->id())
        ->select('_id', 'name', 'phone_number')
        ->orderBy('name')
        ->get();
})->middleware('auth');


Route::get('/broadcast-groups/contacts', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Contact::where(
            'user_id',
            (string) auth()->id()
        )->get([
            '_id',
            'name',
            'phone_number'
        ])
    ]);
})->middleware('auth');
Route::get('/broadcast-groups/contacts', [ContactsController::class, 'listForBroadcast'])
    ->middleware('auth');

    Route::post('/broadcast-groups/send', [BroadcastGroupController::class, 'send']);
    /*
    |--------------------------------------------------------------------------
    | QR BUILDER (SEPARATE MODULE)
    |--------------------------------------------------------------------------
    */
    Route::prefix('qr')->group(function () {

        Route::get('/builder', [QrBuilderController::class, 'index'])->name('qr.builder');
        Route::get('/builder/create', [QrBuilderController::class, 'create'])->name('qr.builder.create');
        Route::post('/store', [QrBuilderController::class, 'store'])->name('qr.store');

        Route::get('/edit/{id}', [QrBuilderController::class, 'edit'])->name('qr.edit');
        Route::post('/update/{id}', [QrBuilderController::class, 'update'])->name('qr.update');
        Route::delete('/delete/{id}', [QrBuilderController::class, 'destroy'])->name('qr.delete');

        Route::get('/preview', [QrBuilderController::class, 'preview'])->name('qr.preview');
        Route::get('/domain', [QrBuilderController::class, 'getDomain'])->name('qr.domain');

});

 });

// PUBLIC BIO (QR / SHARE)
Route::get('/b/{slug}', [BioPageController::class, 'view'])
    ->name('bio.view');

Route::get('/qr/{id}/download-svg', function ($id) {
    $qr = \App\Models\QrLink::findOrFail($id);

    $path = storage_path('app/public/' . $qr->qr_image_path);

    if (!file_exists($path)) {
        abort(404);
    }

    return response(
        file_get_contents($path),
        200,
        ['Content-Type' => 'image/svg+xml']
    );
})->name('qr.download.svg');
Route::post('/qr/reserve-code', function () {
    do {
        $code = \Illuminate\Support\Str::random(8);
    } while (\App\Models\QrCode::where('short_url', $code)->exists());

    return response()->json([
        'success' => true,
        'short_code' => $code,
'scan_url' => url("/qr/{$code}")
    ]);
    
});
// Route::get('/qr/{code}/scan', [QrBuilderController::class, 'scan'])
//     ->where('code', '[A-Za-z0-9]+')
//     ->name('qr.scan');

// Route::get('/qr/{code}', function ($code) {
//     return redirect("/qr/{$code}/scan");
// ✅ FINAL & ONLY QR SCAN ROUTE
Route::get('/qr/{code}', [QrBuilderController::class, 'scan'])
    ->where('code', '[A-Za-z0-9]+');

