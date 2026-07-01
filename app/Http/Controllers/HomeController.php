<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $frontend = rtrim((string) config('app.frontend_url'), '/');

        return redirect()->away($frontend);
    }
}
