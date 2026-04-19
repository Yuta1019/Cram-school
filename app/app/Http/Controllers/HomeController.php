<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home screen.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return redirect()->route('top');
    }
}
