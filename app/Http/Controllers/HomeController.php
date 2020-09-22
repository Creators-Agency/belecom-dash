<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Charts\Analytics;

class HomeController extends Controller {
    public function home() {
        $analytics = new Analytics;
        $analytics->labels(['Jan', 'Feb', 'Mar']);
        $analytics->dataset('Users by trimester', 'line', [10, 25, 13]);
        return view('dashboard', [
            'analytics' => $analytics
        ]);
    }
}