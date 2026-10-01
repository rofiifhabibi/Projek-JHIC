<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $spaIndex = public_path('index.html');
    if (file_exists($spaIndex)) {
        return response()->file($spaIndex, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }
    return view('welcome');
});

Route::fallback(function () {
    $spaIndex = public_path('index.html');
    if (file_exists($spaIndex)) {
        return response()->file($spaIndex, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }
    return response()->json(['message' => 'Not Found'], 404);
});
