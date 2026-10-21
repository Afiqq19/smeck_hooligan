<?php

namespace App\Http\Controllers;

use App\Models\Chant;
use App\Models\Chapter;
use App\Models\MatchTicket;
use App\Models\SmeckPartner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Menampilkan Landing Page Utama SMeCK Hooligan.
     */
    public function index(): View
    {
        $featuredChants = Chant::where('is_featured', true)->take(3)->get();
        $upcomingMatch = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->first();
        $chaptersCount = Chapter::where('is_active', true)->count();
        $partners = SmeckPartner::where('is_active', true)->take(4)->get();

        return view('pages.landing', compact(
            'featuredChants',
            'upcomingMatch',
            'chaptersCount',
            'partners'
        ));
    }
}
