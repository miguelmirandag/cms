<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    /**
     * Show iChannels Portfolio
     *
     * @param  None
     * @return View
     */
    public function show($auth)
    {
        return view('channel.portfolio', ['auth' => $auth]);
    }
}
