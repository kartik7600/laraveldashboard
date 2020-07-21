<?php

namespace App\Http\Controllers\Admin;

use App\TRN;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\ClientDetail;
use App\Contract;
use App\ContractDetail;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

use Validator, Redirect, Hash, DB, Session, Auth, Mail;
use App\History;

class TRNsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.role')
                ->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request);

        // contract
        $contract = Contract::findOrFail($request->contract_id);
        //dd($contract);

        // contract detail
        $contractDetail = ContractDetail::
                            where([
                                    ['contract_id', $contract->cid],
                                    //['contract_end_date', '>=', date('Y-m-d')]
                                ])
                            ->orderBy('contract_end_date', 'ASC')
                            ->first();
        //dd($contractDetail);


        // contract service
        $contractServices = array();

        if( $contractDetail ) :
            $contractServices = DB::table('contract_services AS CS')
                    ->select('S.service_name')
                    ->join('contract_details AS CD', function($join)
                        {
                            $join->on('CS.contract_detail_id', '=', 'CD.cd_id');
                        })
                    ->join('services AS S', function($join)
                        {
                            $join->on('CS.service_id', '=', 'S.sid');
                        })
                    ->where('CS.contract_detail_id', '=', $contractDetail->cd_id)
                    ->orderBy('S.service_name', 'ASC')
                    ->get();
        endif;
        //dd($contractServices);

        // client detail
        $clientDetail = ClientDetail::
                            findOrFail($contract->client_detail_id);
        //dd($clientDetail);

        // user Details
        $clientUser = $clientDetail->user;
        //dd($clientUser);

        // trn
        $TrnDatas = TRN::where('contract_id', $contract->cid)
                        ->orderBy('trn_tax_register_number', 'ASC')
                        ->get();
        //dd($TrnDatas);

        return view('admin.trns.index', compact('clientDetail', 'clientUser', 'contractDetail', 'contractServices', 'TrnDatas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $contract = Contract::findOrFail($request->id);
        //dd($contract);

        return view('admin.trns.create', compact('contract'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd(Input::All());

        $inputs = Input::only(
            'trn_tax_register_number',
            'trn_tax_register_date',
            'trn_tax_period',
            'trn_first_tax_period_start',
            'trn_first_tax_period_start_year',
            'trn_first_tax_period_end',
            'trn_first_tax_period_end_year',
            'trn_vat_certificate',
            'trn_trade_license',
            'trn_company_type'
        );

        $rules = [
            'trn_tax_register_number' => 'required|numeric|min:15', //unique:trns
            'trn_tax_register_date' =>   'required',
            'trn_tax_period' =>   'required',
            'trn_first_tax_period_start' =>   'required',
            'trn_first_tax_period_start_year' =>   'required',
            'trn_first_tax_period_end' =>  'required',
            'trn_first_tax_period_end_year' =>   'required',
            'trn_vat_certificate' => 'nullable|mimes:pdf',
            'trn_trade_license' => 'nullable|mimes:pdf',
            'trn_company_type' => 'required',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $TRNDetail = new TRN;

        $TRNDetail->client_detail_id = Input::get('client_detail_id');
        $TRNDetail->contract_id = Input::get('contract_id');
        $TRNDetail->trn_tax_register_number = Input::get('trn_tax_register_number');
        $TRNDetail->trn_tax_register_date = Input::get('trn_tax_register_date');
        $TRNDetail->trn_tax_period = Input::get('trn_tax_period');
        $TRNDetail->trn_first_tax_period_start = Input::get('trn_first_tax_period_start');
        $TRNDetail->trn_first_tax_period_start_year = Input::get('trn_first_tax_period_start_year');
        $TRNDetail->trn_first_tax_period_end = Input::get('trn_first_tax_period_end');
        $TRNDetail->trn_first_tax_period_end_year = Input::get('trn_first_tax_period_end_year');
        $TRNDetail->trn_company_type = Input::get('trn_company_type');

        // VAT Certificate
        if( Input::hasFile('trn_vat_certificate') )
        {
            if( Input::file('trn_vat_certificate')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/documents'; // upload path

                $extension = Input::file('trn_vat_certificate')->getClientOriginalExtension(); // getting image extension
                $fileName = 'trn_vat_certificate_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('trn_vat_certificate')->move($destinationPath, $fileName);
                
                $TRNDetail->trn_vat_certificate = $fileName;
            }
        }

        // Trade License
        if( Input::hasFile('trn_trade_license') )
        {
            if( Input::file('trn_trade_license')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/documents'; // upload path

                $extension = Input::file('trn_trade_license')->getClientOriginalExtension(); // getting image extension
                $fileName = 'trn_trade_license_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('trn_trade_license')->move($destinationPath, $fileName);

                $TRNDetail->trn_trade_license = $fileName;
            }
        }

        $TRNDetail->save();

        if( $TRNDetail->tid > 0 )
        {
            // store history start
            $clientDtail = ClientDetail::findOrFail($TRNDetail->client_detail_id);

            $activity = 'New Tax Register Number '.$TRNDetail->trn_tax_register_number.' added';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end
  
            Session::flash('messageType', 'success');
            Session::flash('message', 'New TRN created successfully.');

            return redirect::route('trns.index', ['contract_id'=>$TRNDetail->contract_id]);
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t create new TRN.');
            return redirect::back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TRN  $tRN
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $TrnDetail = TRN::findOrFail($id);

        return view('admin.trns.show', compact('TrnDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TRN  $tRN
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $TrnDetail = TRN::findOrFail($id);

        // contract detail start
        $contractDetail = ContractDetail::
                            where('contract_id', $TrnDetail->contract_id)
                            ->first();

        if( $contractDetail->contract_end_date <= date('Y-m-d') )
        {
            return redirect::route('trns.index', ['contract_id'=>$TrnDetail->contract_id]);
        }
        //dd($contrantDetail);
        // contract detail end

        return view('admin.trns.edit', compact('TrnDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TRN  $tRN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd(Input::All());
        $TRNDetail = TRN::findOrFail($id);

        $inputs = Input::only(
            'trn_tax_register_number',
            'trn_tax_register_date',
            'trn_tax_period',
            'trn_first_tax_period_start',
            'trn_first_tax_period_start_year',
            'trn_first_tax_period_end',
            'trn_first_tax_period_end_year',
            'trn_vat_certificate',
            'trn_trade_license',
            'trn_company_type'
        );

        $rules = [
            'trn_tax_register_number' => ['required','numeric', 'min:15'], //Rule::unique('trns')->ignore($TRNDetail->tid, 'tid')
            'trn_tax_register_date' =>   'required',
            'trn_tax_period' =>   'required',
            'trn_first_tax_period_start' =>   'required',
            'trn_first_tax_period_start_year' =>   'required',
            'trn_first_tax_period_end' =>  'required',
            'trn_first_tax_period_end_year' =>  'required',
            'trn_vat_certificate' => 'nullable|mimes:pdf',
            'trn_trade_license' => 'nullable|mimes:pdf',
            'trn_company_type' => 'required'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $TRNDetail->trn_tax_register_number = Input::get('trn_tax_register_number');
        $TRNDetail->trn_tax_register_date = Input::get('trn_tax_register_date');
        $TRNDetail->trn_tax_period = Input::get('trn_tax_period');
        $TRNDetail->trn_first_tax_period_start = Input::get('trn_first_tax_period_start');
        $TRNDetail->trn_first_tax_period_start_year = Input::get('trn_first_tax_period_start_year');
        $TRNDetail->trn_first_tax_period_end = Input::get('trn_first_tax_period_end');
        $TRNDetail->trn_first_tax_period_end_year = Input::get('trn_first_tax_period_end_year');
        $TRNDetail->trn_company_type = Input::get('trn_company_type');

        // VAT Certificate
        if( Input::hasFile('trn_vat_certificate') )
        {
            if( Input::file('trn_vat_certificate')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/documents'; // upload path

                $extension = Input::file('trn_vat_certificate')->getClientOriginalExtension(); // getting image extension
                $fileName = 'trn_vat_certificate_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('trn_vat_certificate')->move($destinationPath, $fileName);
                $TRNDetail->trn_vat_certificate = $fileName;
            }
        }

        // Trade License
        if( Input::hasFile('trn_trade_license') )
        {
            if( Input::file('trn_trade_license')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/documents'; // upload path

                $extension = Input::file('trn_trade_license')->getClientOriginalExtension(); // getting image extension
                $fileName = 'trn_trade_license_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('trn_trade_license')->move($destinationPath, $fileName);
                $TRNDetail->trn_trade_license = $fileName;
            }
        }

        $TRNDetail->save();

        if( $TRNDetail->tid > 0 )
        {
            // store history start
            $clientDtail = ClientDetail::findOrFail($TRNDetail->client_detail_id);
            
            $activity = 'Tax Register Number '.$TRNDetail->trn_tax_register_number.' updated';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'TRN updated successfully.');

            return redirect::route('trns.index', ['contract_id'=>$TRNDetail->contract_id]);
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t update TRN.');
            return redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TRN  $tRN
     * @return \Illuminate\Http\Response
     */
    public function destroy(TRN $tRN)
    {
        //
    }
}
