<?php

namespace App\Http\Controllers\Admin;

use App\ClientService;
use App\ClientContract;
use App\ClientCotractService;
use App\ClientUploadReport;
use App\User;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator, Redirect, DB, Session, Auth;

class ClientServicesController extends Controller
{
    public function userPermission($roles=array())
    {
        if( !in_array(Auth::user()->user_role, $roles) )
        {
            return redirect::route('a.dashboard');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( $this->userPermission(array('admin', 'account_manager')) ) {
            return $this->userPermission();
        }

        /*$clientUsers = User::where([
                                    ['user_status', '=', 'Active'],
                                    ['client_status', '=', 'Approved'],
                                    ['user_role', '=', 'client'],
                                ])
                                ->orderBy('user_company', 'ASC')
                                ->get();*/

        $clientUsers = DB::table('users as U')
                                ->select('U.*', 'UM.user_company AS manager_user_company')
                                ->leftJoin('users AS UM', 'UM.id', '=', 'U.account_manager_id')
                                ->where([
                                    ['U.client_status', '=', 'Approved'],
                                    ['U.user_role', '=', 'client'],
                                ]);

        if( Auth::user()->user_role == 'account_manager' )
        {
        	$clientUsers->where([
					                ['U.account_manager_id', '=', Auth::user()->id],
					            ]);
        }

		$clientUsers = $clientUsers ->orderBy('U.user_company', 'ASC')
                                	->get();

        return view('admin.clients.index', compact('clientUsers') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if( $this->userPermission(array('admin')) ) {
            return $this->userPermission();
        } 

        $clientUser = User::findOrFail($id);

        //DB::enableQueryLog();
        $Services = DB::table("services")->select('*')
	                    ->whereNOTIn('id', function($query) use ($id)
	                    {
	                        $query->select('service_id')
	                                ->from('client_service')
	                                ->where([
	                                			['user_id', '=', $id],
	                                			['expiry_date', '>',
	                                			date('Y-m-d')]
	                                		]);

	                    })
	                    ->get();
        
        //dd(DB::getQueryLog());
                    
        return view('admin.clients.create', compact('clientUser', 'Services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( $this->userPermission(array('admin')) ) {
            return $this->userPermission();
        }

        $inputs = Input::only(
            'service_id',
            'purchase_date',
            'service_duration',
            'client_service_amount'
        );

        $rules = [
            'service_id' => 'required',
            'purchase_date'  =>  'required|date',
            'service_duration' => 'required',
            'client_service_amount'  =>  'required|numeric'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $service = new ClientService;

        $service->user_id = Input::get('user_id');
        $service->service_id = Input::get('service_id');
        $service->purchase_date = Input::get('purchase_date');
        $service->client_service_amount = Input::get('client_service_amount');
        $service->service_duration = Input::get('service_duration');
        $service->expiry_date = date('Y-m-d', strtotime('+'.$service->service_duration.'', strtotime(Input::get('purchase_date'))));

        $service->save();

        Session::flash('messageType', 'success');
        Session::flash('message', 'User service added successfully.');

        return redirect::route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientService  $clientService
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$clientData = User::find($id);

        $clientData = DB::table('users as U')
                            ->select('U.*', 'UM.user_company AS manager_user_company')
                            ->leftJoin('users AS UM', 'UM.id', '=', 'U.account_manager_id')
                            ->where([
                                		['U.id', '=', $id]
                            		])
                            ->first();

        // client contract
        $clientContracts = ClientContract::where([
                                                    ['client_id', '=', $id],
                                                    //['contract_end_date', '>', date('Y-m-d')]
                                                ])
                            ->orderBy('contract_end_date', 'ASC')
                            ->get();
        
        //$clientservices = ClientContract::find($clientContracts['0']->id);
        //$services = $clientservices->clientcontractservices;
        //dd($services);
                            
        // Total amont
        /*$ContractAmount = ClientService::where('user_id', '=', $id)
                        	->sum(DB::raw('client_service_amount'));*/

        // Client services
        /*$ClientServices = DB::table('client_service AS CS')
		                    ->select('*', 'CS.id AS cs_id')
		                    ->join('services AS S', function($join)
		                        {
		                            $join->on('CS.service_id', '=', 'S.id');
		                        })
		                    ->where('CS.user_id', '=', $id)
		                    ->orderBy('CS.purchase_date', 'DESC')
		                    ->get();*/
        //dd($ClientServices);

        // Client reports
        $clientReports = ClientUploadReport::where('user_id', $id)
	                        ->orderby('id', 'DESC')
	                        ->get();

        return view('admin.clients.show', compact('clientData', 'clientContracts', 'clientReports'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClientService  $clientService
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( $this->userPermission(array('admin')) ) {
            return $this->userPermission();
        }

        $dataRow = User::findOrFail($id);
        //dd($userda);
        return view('admin.clients.edit', compact('dataRow'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientService  $clientService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientService $clientService)
    {
        if( $this->userPermission(array('admin')) ) {
            return $this->userPermission();
        }

        $inputs = Input::only(
            'client_name',
            'amount'
        );

        $rules = [
            'client_name' => 'required',
            'amount'  =>  'required|numeric'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $service = ClientService::find($clientService->id);;

        $service->user_id = Auth::user()->id;
        $service->client_name = Input::get('client_name');
        $service->service_amount = Input::get('amount');

        $service->save();

        Session::flash('messageType', 'success');
        Session::flash('message', 'Service updated successfully.');

        return redirect::route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClientService  $clientService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( $this->userPermission(array('admin')) ) {
            return $this->userPermission();
        }

        //dd($id);
        $clientService = ClientService::find($id);
        $clientService->delete();

        Session::flash('messageType', 'success');
        Session::flash('message', 'Client Service deleted successfully.');

        return redirect::back();
        //return redirect::route('clients.show', ['clients'=> $id]);
    }
}
