<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class WidgetController extends Controller
{
    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function show()
    {
        return view('widget');
    }
}
