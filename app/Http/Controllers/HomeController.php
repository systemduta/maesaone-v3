<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function privacy()
    {
        return view('home.privacy');
    }
    public function term()
    {
        return view('home.term');
    }
    public function faq()
    {
        return view('home.faq');
    }
    public function contact()
    {
        return view('home.contact');
    }

    public function index()
    {
        return view('home.index');
    }
}