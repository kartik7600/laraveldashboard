<?php

namespace App\Http\Controllers\Admin;

use App\VatReportSubmitted;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\ClientDetail;
use App\TRN;
use App\CompanyDetail;
use App\Contract;
use App\ContractDetail;
use App\History;

use App\Mail\VatReprotSubmit;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, DB, Session, Auth, File, Mail;

class VatReportSubmittedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('manager.role');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // trn detail
        $TrnData = TRN::findOrFail($request->id);

        // contract deatil
        $contractDetail = DB::table('contract_details AS ConD')
                            ->join('contracts AS C', function($join)
                                {
                                    $join->on('ConD.contract_id', '=', 'C.cid');
                                })
                            ->join('trns AS T', function($join)
                                {
                                    $join->on('C.cid', '=', 'T.contract_id');
                                })
                            ->where([
                                ['T.tid', '=', $TrnData->tid]
                            ])
                            ->first();

        //dd($contractDetail);

        $VATReportsSubmitted = array();

        if( $contractDetail )
        {
            $vatReportsSubmit = VatReportSubmitted::
                                where([
                                    ['contract_detail_id', $contractDetail->cd_id],
                                    ['trn_id', $contractDetail->tid],
                                    ['vat_report_tax_period', $contractDetail->trn_tax_period]
                                ])
                                ->get();
            //dd($vatReportsSubmit);

            if( count($vatReportsSubmit) > 0 )
            {
                foreach( $vatReportsSubmit as $vatReportSubmit )
                {
                    $VATReportsSubmitted[] = $vatReportSubmit->vat_report_submission_deadline;
                }
            }
            //dd($VATReportsSubmitted);
        }

        return view('admin.vat_report_submitted.create', compact('TrnData', 'contractDetail', 'VATReportsSubmitted'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vatReport = new VatReportSubmitted;

        $vatReport->manager_id = Auth::user()->uid; 
        $vatReport->contract_detail_id = Input::get('contract_detail_id');
        $vatReport->trn_id = Input::get('trn_id');
        $vatReport->vat_report_tax_period = Input::get('vat_report_tax_period');
        $vatReport->vat_report_duration = Input::get('vat_report_duration');
        $vatReport->vat_report_submission_deadline = Input::get('vat_report_submission_deadline');

        $vatReport->save();

        if( $vatReport->vrsid > 0 )
        {
            $client = DB::table('users AS U')
                            ->join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                            ->join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                            ->join('contract_details AS ConD', 'C.cid', '=', 'ConD.contract_id')
                            ->join('trns AS T', 'C.cid', '=', 'T.contract_id')
                            ->where([
                                      ['ConD.cd_id', '=', $vatReport->contract_detail_id],
                                    ])
                            ->first();
            //dd($client);

            $user = User::select('email')
                        ->findOrFail($client->uid);

            if( $user )
            {
                $user->user_first_name = $client->user_first_name;
                $user->trn_tax_register_number = $client->trn_tax_register_number;
                $user->vat_report_duration = $vatReport->vat_report_duration;

                Mail::to($user->email)->send(new VatReprotSubmit($user));


                // store history start
                $activity = 'Tax Register Number '.$client->trn_tax_register_number.' duration '.$vatReport->vat_report_duration.' VAT Report Submitted';
                
                $history = History::create([
                        'added_by_id' => Auth::user()->uid,
                        'user_id' => $client->uid,
                        'activity' => $activity
                    ]);
                // store history end

                echo 'success';
            }
            else
            {
                echo "error";
            }
        }
        else
        {
            echo "error";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VatReportSubmitted  $vatReportSubmitted
     * @return \Illuminate\Http\Response
     */
    public function show(VatReportSubmitted $vatReportSubmitted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VatReportSubmitted  $vatReportSubmitted
     * @return \Illuminate\Http\Response
     */
    public function edit(VatReportSubmitted $vatReportSubmitted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VatReportSubmitted  $vatReportSubmitted
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VatReportSubmitted $vatReportSubmitted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VatReportSubmitted  $vatReportSubmitted
     * @return \Illuminate\Http\Response
     */
    public function destroy(VatReportSubmitted $vatReportSubmitted)
    {
        //
    }
}
