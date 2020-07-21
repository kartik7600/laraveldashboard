<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ClientUploadedReport;
use App\User;
use App\Service;
use App\History;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, DB, Session, Auth, File;

class ClientUploadedReportsController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientReports = ClientUploadedReport::where('user_id', Auth::user()->uid)
            				->orderby('curid', 'DESC')
            				->get();

        return view('admin.clients.uploaded-reports.index', compact('clientReports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');

        return view('admin.clients.uploaded-reports.create', compact('services'));
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
            'service_id',
            'report_file'
        );

        $rules = [
            'service_id' => 'required',
            'report_file' => 'required|mimes:pdf',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        // Insert record
        $clientReport = new ClientUploadedReport;

        $clientReport->user_id = Auth::user()->uid;
        $clientReport->service_id = (Input::get('service_id'));

        if( Input::hasFile('report_file') )
        {
            if( Input::file('report_file')->isValid() )
            {
                $destinationPath = 'public/uploads/clients_reports'; // upload path

                $OriginalFilename = Input::file('report_file')->getClientOriginalName(); // getting image extension

                $fileParts = pathinfo($OriginalFilename);
                $fileName = $fileParts['filename'].'_'.date('Ymdhms').'.'.$fileParts['extension'];

                Input::file('report_file')->move($destinationPath, $fileName);
                $clientReport->report_file = $fileName;
            }
        }

        
        $clientReport->save();

        if( $clientReport->curid > 0 )
        {
            $service = Service::
                        where('sid', Input::get('service_id'))
                        ->first();

            // store history start
            $activity = $service->service_name.' service report uploaded';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Reprort uploaded successfully.');

            return redirect::route('uploaded-reports.index');
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t reprort upload.');

        return redirect::back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $clientService = ClientUploadedReport::find($id);

        $service_id = $clientService->service_id;

        // delete report file
        $file = public_path('uploads/clients_reports/'.$clientService->report_file);

        if( !empty($clientService->report_file) && File::exists($file) )
        {
            unlink($file);
        }

        
        if( $clientService->delete() )
        {
            $service = Service::
                        where('sid', $service_id)
                        ->first();

            // store history start
            $activity = $service->service_name.' service report deleted';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Report deleted successfully.');
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t delete reprot.');
        }

        return redirect::back();
    }
}
