<?php

namespace HichemTabTech\JetstreamRise\Http\Controllers\Livewire;

use HichemTabTech\JetstreamRise\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return view('terms', [
            'terms' => Str::markdown(file_get_contents($termsFile)),
        ]);
    }
}
