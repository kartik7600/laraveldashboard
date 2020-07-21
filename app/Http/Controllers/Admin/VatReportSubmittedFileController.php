<?php

namespace App\Http\Controllers\Admin;

use App\VatReportSubmittedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, Hash, DB, Session, Auth, File, Mail;
use App\User;
use App\History;

use App\Mail\VatReportSubmittedFiles;

class VatReportSubmittedFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $TrnID = $request->id;

        $vatReports = VatReportSubmittedFile::
                            where([
                                ['trn_id', $TrnID]
                            ])
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return view('admin.vat_report_submitted_files.index', compact('TrnID', 'vatReports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $TrnID = $request->id;
        
        return view('admin.vat_report_submitted_files.create', compact('TrnID'));
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
            'vat_report_file_name',
            'vat_report_file'
        );

        $rules = [
            'vat_report_file_name' => 'required',
            'vat_report_file' => 'required|mimes:pdf',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }


        // Insert record
        $vatReport = new VatReportSubmittedFile;

        $vatReport->manager_id = Auth::user()->uid;
        $vatReport->trn_id = (Input::get('trn_id'));
        $vatReport->vat_report_file_name = (Input::get('vat_report_file_name'));

        if( Input::hasFile('vat_report_file') )
        {
            if( Input::file('vat_report_file')->isValid() )
            {
                $destinationPath = 'public/uploads/vat-report-submitted-files'; // upload path

                $OriginalFilename = Input::file('vat_report_file')->getClientOriginalName(); // getting image extension

                $fileParts = pathinfo($OriginalFilename);
                $fileName = 'vat-report-submitted-file_'.$fileParts['filename'].'_'.date('Ymdhms').'.'.$fileParts['extension'];

                Input::file('vat_report_file')->move($destinationPath, $fileName);
                $vatReport->vat_report_file = $fileName;
            }
        }


        $vatReport->save();

        if( $vatReport->vrsfid > 0 )
        {
            $client = DB::table('users AS U')
                            ->join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                            ->join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                            ->join('trns AS T', 'C.cid', '=', 'T.contract_id')
                            ->where([
                                      ['T.tid', '=', $vatReport->trn_id],
                                    ])
                            ->first();
            //dd($client);

            $user = User::select('email')
                        ->findOrFail($client->uid);

            if( $user )
            {
                $user->user_first_name = $client->user_first_name;
                $user->client_number = $client->client_number;
                $user->vat_report_file = $vatReport->vat_report_file;

                Mail::to($user->email)
                    ->send(new VatReportSubmittedFiles($user));
            }

            // store history start
            $activity = 'Tax Register Number '.$client->trn_tax_register_number.' '.(Input::get('vat_report_file_name')).' VAT report upload';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $client->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'VAT Report added successfully.');

            return redirect::route('vat-report-submitted-file.index', ['id'=>$vatReport->trn_id]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t VAT Report add.');

        return redirect::back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VatReportSubmittedFile  $vatReportSubmittedFile
     * @return \Illuminate\Http\Response
     */
    public function show(VatReportSubmittedFile $vatReportSubmittedFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VatReportSubmittedFile  $vatReportSubmittedFile
     * @return \Illuminate\Http\Response
     */
    public function edit(VatReportSubmittedFile $vatReportSubmittedFile)
    {
        $vatReport = VatReportSubmittedFile::findOrFail($vatReportSubmittedFile->vrsfid);

        return view('admin.vat_report_submitted_files.edit', compact('vatReport'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VatReportSubmittedFile  $vatReportSubmittedFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VatReportSubmittedFile $vatReportSubmittedFile)
    {
        $inputs = Input::only(
            'vat_report_file_name',
            'vat_report_file'
        );

        $rules = [
            'vat_report_file_name' => 'required',
            'vat_report_file' => 'nullable|mimes:pdf',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        // Insert record
        $vatReport = VatReportSubmittedFile::findOrFail($vatReportSubmittedFile->vrsfid);

        $vatReport->vat_report_file_name = (Input::get('vat_report_file_name'));

        if( Input::hasFile('vat_report_file') )
        {
            if( Input::file('vat_report_file')->isValid() )
            {
                $destinationPath = 'public/uploads/vat-report-submitted-files'; // upload path

                $oldFile = public_path('uploads/vat-report-submitted-files/'.$vatReport->vat_report_file);
                if( !empty($vatReport->vat_report_file) && File::exists($oldFile) )
                {
                    unlink($oldFile);
                }

                $OriginalFilename = Input::file('vat_report_file')->getClientOriginalName(); // getting image extension

                $fileParts = pathinfo($OriginalFilename);
                $fileName = 'vat-report-submitted-file_'.$fileParts['filename'].'_'.date('Ymdhms').'.'.$fileParts['extension'];

                Input::file('vat_report_file')->move($destinationPath, $fileName);
                $vatReport->vat_report_file = $fileName;
            }
        }


        $vatReport->save();

        if( $vatReport->vrsfid > 0 )
        {
            $client = DB::table('users AS U')
                            ->join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                            ->join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                            ->join('trns AS T', 'C.cid', '=', 'T.contract_id')
                            ->where([
                                      ['T.tid', '=', $vatReport->trn_id],
                                    ])
                            ->first();
            //dd($client);

            if( Input::hasFile('vat_report_file') )
            {
                $user = User::select('email')
                            ->findOrFail($client->uid);

                if( $user )
                {
                    $user->user_first_name = $client->user_first_name;
                    $user->client_number = $client->client_number;
                    $user->vat_report_file = $vatReport->vat_report_file;

                    Mail::to($user->email)
                        ->send(new VatReportSubmittedFiles($user));
                }
            }

            // store history start
            $activity = 'Tax Register Number '.$client->trn_tax_register_number.' '.(Input::get('vat_report_file_name')).' VAT report uploaded update';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $client->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'VAT Report uploaded successfully.');

            return redirect::route('vat-report-submitted-file.index', ['id'=>$vatReport->trn_id]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t VAT Report upload.');

        return redirect::back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VatReportSubmittedFile  $vatReportSubmittedFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(VatReportSubmittedFile $vatReportSubmittedFile)
    {
        $vatReport = VatReportSubmittedFile::findOrFail($vatReportSubmittedFile->vrsfid);

        $trn_id = $vatReport->trn_id;
        $vat_report_file_name = $vatReport->vat_report_file_name;

        // delete report file
        $file = public_path('uploads/vat-report-submitted-files/'.$vatReport->vat_report_file);

        if( !empty($vatReport->vat_report_file) && File::exists($file) )
        {
            unlink($file);
        }

        if( $vatReport->delete() )
        {
            $client = DB::table('users AS U')
                        ->join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                        ->join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                        ->join('trns AS T', 'C.cid', '=', 'T.contract_id')
                        ->where([
                                  ['T.tid', $trn_id],
                                ])
                        ->first();

            // store history start
            $activity = 'Tax Register Number '.$client->trn_tax_register_number.' '.$vat_report_file_name.' VAT report uploaded delete';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $client->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'VAT Report deleted successfully.');
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t delete VAT Report.');
        }

        return redirect::back();
    }
}
