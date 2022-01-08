<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        redirect();
        return view('home', ['books' => Book::all()]);
    }

    public function adminHome() {
        return view('home', ['books' => Book::all()]);
    }

    public function howTo() {
        return view('howTo');
    }
}
