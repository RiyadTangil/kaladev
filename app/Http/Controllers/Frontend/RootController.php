<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Status;
use App\Models\Analytic;
use App\Models\ThemeSetting;
use App\Http\Controllers\Controller;

class RootController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application
    {
        // Hole alle aktiven Analytik-Daten mit den zugehörigen Sektionen
        $analytics = Analytic::with('analyticSections')->where(['status' => Status::ACTIVE])->get();
        
        // Hole das ThemeSetting für das Favicon
        $themeFavicon = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();

        // Überprüfe, ob das Favicon-Setting existiert
        if ($themeFavicon && isset($themeFavicon->faviconLogo)) {
            $favIcon = $themeFavicon->faviconLogo;
        } else {
            // Setze einen Standardwert, falls kein Favicon gefunden wurde
            $favIcon = 'default-favicon.png'; // Standard-Favicon setzen
        }

        // Gebe die View mit den nötigen Daten zurück
        return view('master', ['analytics' => $analytics, 'favicon' => $favIcon]);
    }
}
