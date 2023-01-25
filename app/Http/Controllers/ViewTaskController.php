<?php

namespace App\Http\Controllers;

class ViewTaskController extends Controller
{
    public function __construct(){}

    public function index()
    {
        return view('task.index');
    }
}
