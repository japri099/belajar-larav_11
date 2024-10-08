<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class HomeController extends Controller
{
    public function index()
{
    $items = Item::all(); // Mengambil semua data dari tabel items
    return view('home', compact('items'));
}
}
