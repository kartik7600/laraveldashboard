<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\User;
use App\Service;
use App\ClientService;
use DB, Mail;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.role');
    }

    public function index()
    {
    	//DB::enableQueryLog();

    	$Clients = DB::table('contract_details AS ConD')
                    ->join('contracts AS C', function($join)
                        {
                            $join->on('ConD.contract_id', '=', 'C.cid');
                        })
                    /*->join('trns AS T', function($join)
                        {
                            $join->on('C.trn_id', '=', 'T.tid');
                        })*/
                    ->join('client_details AS CD', function($join)
                        {
                            $join->on('C.client_detail_id', '=', 'CD.cdid');
                        })
                    ->join('users AS U', function($join)
                        {
                            $join->on('CD.user_id', '=', 'U.uid');
                        })
                    ->whereBetween('ConD.contract_end_date', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->orderBy('ConD.contract_end_date', 'ASC')
                    ->get();

        //dd(DB::getQueryLog());
		//dd($Clients);


        // trn
        $TrnDatas = DB::table('trns AS T')
                    ->select('T.*', 'CD.*', 'U.user_first_name', 'U.user_last_name')
                    ->join('client_details AS CD', function($join)
                        {
                            $join->on('T.client_detail_id', '=', 'CD.cdid');
                        })
                    ->join('users AS U', function($join)
                        {
                            $join->on('CD.user_id', '=', 'U.uid');
                        })
                    ->orderBy('T.trn_tax_register_number', 'ASC')
                    ->get();

    	return view('admin.dashboard', compact('Clients', 'TrnDatas'));
    }


    public function userApproved($id)
    {
        
        //dd($id);
        $userd = User::find($id);
        
        $userd->client_status = 'Approved';
    
        $userd->save();
       
        if($userd->client_status == 'Approved'){
            //$data = array('name'=> $userd->user_firstname);
            Mail::send('admin.email',['firstname' => $userd->user_firstname], function($message) use ($userd)
            {
                $message->to($userd->email, 'Themmar')->subject
                    ('Themmar');
                $message->from('info@Themaar.ae','Themmar Dashboard');
            });
        }else{
            
        }
        return redirect()->back()->with('message', 'User Approved Successfully');
    }


    public function destroy($id) {
        //DB::delete('delete from users where id = ?',[$id]);
        //echo "User deleted successfully.<br/>";
        $userd = User::find($id);
        $userd->delete();
        
        return redirect()->back()->with('message', 'User deleted successfully');
    }
}