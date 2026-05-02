<?php

namespace App\Http\Controllers;

use App\Models\AnakYatim;
use App\Models\Berita;

class HomeController extends Controller
{
    public function index()
    {
        $totalAnak = AnakYatim::aktif()->count();

        // Hitung tahun berdiri dari April 1992
        $tahunBerdiri = \Carbon\Carbon::create(1992, 4, 1)->diffInYears(now());

        // Ambil 3 berita terbaru yang sudah published
        $beritaTerbaru = Berita::latest3()->get();

        return view('home', compact('totalAnak', 'tahunBerdiri', 'beritaTerbaru'));
    }
}
