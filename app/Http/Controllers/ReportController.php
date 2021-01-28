<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ReportController extends Controller
{
    public function index()
    {
        return view('report.home');
    }

    public function actual()
    {
        $data = DB::table('beneficiaries')
                ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                ->join('accounts','accounts.beneficiary', '=','beneficiaries.identification')
                ->where('beneficiaries.isActive',3)
                ->where('accounts.productNumber','!=',NULL)
                ->get();
        return view('report.clients',[
            'clients' => $data,
        ]);
    }
}