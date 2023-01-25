<?php

namespace App\Http\Controllers;

class ViewListController extends Controller
{
    public function __construct(){}

    public function index()
    {
        return view('list.index');
    }
}
