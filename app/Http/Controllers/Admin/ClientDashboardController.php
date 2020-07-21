<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Contract;
use Auth, DB;

class ClientDashboardController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('client.role');
    }

    public function index()
    {
        $user_id = Auth::user()->uid;
        $userData = User::findOrFail($user_id);

        // Client Details
        $clientDetail = $userData->clientDetails;

        // TRN Details
        //$trnDetails = $clientDetail->trns;

        // client contract
        $clientContracts = DB::table('contract_details AS ConD')
                            ->join('contracts AS C', function($join)
                                {
                                    $join->on('ConD.contract_id', '=', 'C.cid');
                                })
                            ->where([
                                ['C.client_detail_id', '=', $clientDetail->cdid]
                            ])
                            ->get();

        //dd($clientContracts);

    	return view('admin.dashboard-client', compact('userData', 'clientDetail', 'clientContracts'));
    }
}
