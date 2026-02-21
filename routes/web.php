<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('pages.loading'))->name('loading');
Route::get('/home', fn() => view('pages.home'))->name('home');

Route::get('/message', fn() => view('pages.message'))->name('message');
Route::get('/gallery', fn() => view('pages.gallery'))->name('gallery');
Route::get('/music', fn() => view('pages.music'))->name('music');
Route::get('/tetris', fn() => view('pages.tetris'))->name('tetris');