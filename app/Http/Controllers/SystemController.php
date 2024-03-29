<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Beneficiary;
use App\Models\Payout;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use DB;
use SweetAlert;

class SystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('system.import');
    }

    public function import(Request $request)
    {
        $file = $request->file;

        $customerArr = $this->csvToArray($file);
        $data = [];
        for ($i = 0; $i < count($customerArr); $i++) {
            $customerArr;
        }
        // DB::table('beneficiaries')->insert($customerArr);
        try {
            DB::table('beneficiaries')->insert($customerArr);
            alert()->success('user importing returned with success', 'Success!');
            return Redirect::back();
        } catch (\Exception $th) {
            alert()->error('unable to import data from file', 'Error!');
            return Redirect::back();
        }
    }
    public function csvToArray($filename = '', $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    // array_push($data,$row);
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients()
    {
        $Get_clients = DB::table('beneficiaries')
            ->join('administrative_locations', 'administrative_locations.id', '=', 'beneficiaries.location')
            ->select(
                'beneficiaries.id as clientID',
                'beneficiaries.identification',
                'beneficiaries.firstname',
                'beneficiaries.lastname',
                'beneficiaries.gender',
                'beneficiaries.DOB',
                'beneficiaries.primaryPhone',
                'administrative_locations.id as locationID',
                'administrative_locations.locationName',
            )
            ->where('beneficiaries.isActive', 0)
            ->where('administrative_locations.status', 1)
            ->get();
        return view('system.clients', [
            'clients' => $Get_clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function specialUpdate()
    {
        return view('system.error');
    }

    public function updateSpecial(Request $request)
    {
        $updatePayout = Payout::where('clientID', $request->existing)
            ->update([
                'clientID', $request->updated
            ]);
        if ($updatePayout) {
            $updateAccount = Account::where('beneficiary', $request->existing)
                ->update([
                    'beneficiary', $request->updated
                ]);
            if ($updateAccount) {
                alert()->success('success', 'Success!');
                return Redirect::back();
            }
            alert()->error('Oops', 'Something Wrong when updating Account!');
            return Redirect::back();
        }
        alert()->error('Oops', 'Something Wrong when updating payout!');
        return Redirect::back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
