<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/admin'));

/**
 * Sign in as the seeded demo user, without typing anything.
 *
 * **Local only, and it aborts anywhere else.** This exists because the whole
 * point of this application is looking at a page, and a sign-in form between a
 * reviewer and that page is friction with no value: there is nothing here to
 * protect. Every row is fixture data and the schema is the product.
 *
 * It is a demo affordance and it must never be copied into a real application.
 */
Route::get('/demo-login', function () {
    abort_unless(app()->environment('local'), 404);

    Auth::login(User::where('email', 'admin@example.com')->firstOrFail());

    return redirect('/admin/database-schema');
})->name('demo.login');
