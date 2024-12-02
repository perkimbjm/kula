<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;


class InformationController extends Controller
{
    public function index()
    {
        $information = Information::all();
        
       
        return view('home', compact('information'));
    }
}
