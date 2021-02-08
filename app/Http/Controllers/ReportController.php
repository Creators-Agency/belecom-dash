<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ReportController extends Controller
{
    public function index()
    {
        return view('report.home',[
            'pageTitle' =>'Add clients'
        ]);
        
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
            'pageTitle' =>'Add clients'

        ]);
    }
    public function perspective()
    {
        $data = DB::table('beneficiaries')
                ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                ->where('beneficiaries.isActive',1)
                ->where('administrative_locations.status',1)
                ->get();
        return view('report.clients',[
            'clients' => $data,
            'pageTitle' =>'Add clients'
        ]);
    }

    public function paid()
    {
        $data = DB::table('beneficiaries')
                ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                ->join('payouts','payouts.clientID', '=','beneficiaries.identification')
                ->where('beneficiaries.isActive',3)
                ->where('payouts.loanStatus',1)
                ->where('administrative_locations.status',1)
                ->get();
        return view('report.clients',[
            'clients' => $data,
            'pageTitle' =>'Add clients'

        ]);
    }

    public function amountDue()
    {
        $date = date('m-Y');
        $data = DB::table('payouts')
                    ->join('beneficiaries','payouts.clientID', '=','beneficiaries.identification')
                    ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                    ->where('payouts.monthYear','<',$date)
                    ->groupBy('beneficiaries.identification')
                    ->get();
        return view('report.clients',[
            'clients' => $data,
            'pageTitle' =>'Add clients'

        ]);
    }

    public function faulty()
    {
        $data = DB::table('solar_panels')
                        ->join('accounts','accounts.productNumber', '=','solar_panels.solarPanelSerialNumber')
                        ->join('beneficiaries','accounts.beneficiary', '=','beneficiaries.identification')
                        ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                        ->where('solar_panels.status',1)
                        ->where('solar_panels.moreInfo',2)
                        ->get();
        return view('report.product',[
            'clients' => $data,
            'pageTitle' =>'Add clients'

        ]);
    }
    public function returned()
    {
        $data = DB::table('solar_panels')
                        ->join('accounts','accounts.productNumber', '=','solar_panels.solarPanelSerialNumber')
                        ->join('beneficiaries','accounts.beneficiary', '=','beneficiaries.identification')
                        ->join('administrative_locations','beneficiaries.location', '=','administrative_locations.id')
                        ->where('solar_panels.status',1)
                        ->where('solar_panels.moreInfo',1)
                        ->get();
        return view('report.product',[
            'clients' => $data,
            'pageTitle' =>'Returned Clients'
        ]);
    }
}