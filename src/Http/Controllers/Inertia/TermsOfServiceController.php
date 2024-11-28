<?php

namespace HichemTabTech\JetstreamRise\Http\Controllers\Inertia;

use HichemTabTech\JetstreamRise\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return Inertia::render('TermsOfService', [
            'terms' => Str::markdown(file_get_contents($termsFile)),
        ]);
    }
}
