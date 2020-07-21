<?php

namespace App\Http\Controllers\Admin;

use App\Service;
use App\ContractService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator, Redirect, DB, Session, Auth;
use App\History;

class ServicesController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::orderby('service_name', 'ASC')->get();
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = Input::only(
            'service_name'
        );

        $rules = [
            'service_name' => 'required|unique:services',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $service = new Service;

        $service->service_name = Input::get('service_name');

        $service->save();

        // store history start
        $activity = 'New service created';
        
        $history = History::create([
                'added_by_id' => Auth::user()->uid,
                'activity' => $activity
            ]);
        // store history end

        Session::flash('messageType', 'success');
        Session::flash('message', 'New service created successfully.');

        return redirect::route('services.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
         $serviceData = Service::find($service->sid);

        $ClientRows = array();
        /*$ClientRows = DB::table('client_service AS CS')
                    ->select('CS.*', 'U.*')
                    ->join('users AS U', function($join)
                        {
                            $join->on('CS.user_id', '=', 'U.id');
                        })
                    ->where('CS.service_id', '=', $service->id)
                    ->orderBy('CS.purchase_date', 'DESC')
                    ->get();*/

        return view('admin.services.show', compact('serviceData', 'ClientRows'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //dd($service->id);
        $dataRow = Service::find($service->sid);
        return view('admin.services.edit', compact('dataRow'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $inputs = Input::only(
            'service_name'
        );

        $rules = [
            'service_name' => 'required'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $service = Service::find($service->sid);

        $service->service_name = Input::get('service_name');

        $service->save();

        // store history start
        $activity = 'Service updated';
        
        $history = History::create([
                'added_by_id' => Auth::user()->uid,
                'activity' => $activity
            ]);
        // store history end

        Session::flash('messageType', 'success');
        Session::flash('message', 'Service updated successfully.');

        return redirect::route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $ServiceCount = ContractService::where('service_id', $service->sid)
                            ->count('service_id');

        if( $ServiceCount > 0 )
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Service not deleted.');
        }
        else
        {
            if( $service->delete() )
            {
                // store history start
                $activity = 'Service deleted';
                
                $history = History::create([
                        'added_by_id' => Auth::user()->uid,
                        'activity' => $activity
                    ]);
                // store history end
                
                Session::flash('messageType', 'success');
                Session::flash('message', 'Service deleted successfully.');
            }
            else
            {
                Session::flash('messageType', 'error');
                Session::flash('message', 'Service not deleted.');
            }
        }

        return redirect::route('services.index');
    }
}
