<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function getIndex()
    {
        return view("mixtra::home");
    }
}
