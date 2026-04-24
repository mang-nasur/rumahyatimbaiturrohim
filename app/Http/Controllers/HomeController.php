<?php

namespace App\Http\Controllers;

use App\Models\AnakYatim;

class HomeController extends Controller
{
    public function index()
    {
        $totalAnak = AnakYatim::aktif()->count();

        // Hitung tahun berdiri dari April 1992
        $tahunBerdiri = \Carbon\Carbon::create(1992, 4, 1)->diffInYears(now());

        return view('home', compact('totalAnak', 'tahunBerdiri'));
    }
}
