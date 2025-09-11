<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;

class IndexController extends Controller
{
    public function index()
    {
        $RoomTypes = RoomType::all();

        return view('index', compact('RoomTypes'));
    }
}
