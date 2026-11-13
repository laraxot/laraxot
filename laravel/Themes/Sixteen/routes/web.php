// Privacy
    Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');

    // Disclaimer
    Route::get('/disclaimer', fn() => view('disclaimer'))->name('disclaimer');

    // Cookie Policy
    Route::get('/cookie-policy', fn() => view('pages.cookie-policy'))->name('cookie-policy');