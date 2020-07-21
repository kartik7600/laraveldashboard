<?php

namespace App\Http\Controllers\Admin;

use App\CompanyDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\TRN;
use App\ContractDetail;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

use Validator, Redirect, Hash, DB, Session, Auth, Mail;
use App\History;
use App\ClientDetail;

class CompaniesDetailController extends Controller
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
        $TRNDetail = TRN::findOrFail($request->id);

        //contract detail 
        $ContractDetails = ContractDetail::where('contract_id', $TRNDetail->contract_id)
                        ->first();
        //dd($ContractDetails);

        $CompanyDetails = CompanyDetail::where('trn_id', $TRNDetail->tid)
                        ->orderBy('company_name', 'ASC')
                        ->get();

        return view('admin.companies.index', compact('TRNDetail', 'ContractDetails', 'CompanyDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $TRNDetail = TRN::findOrFail($request->id);

        return view('admin.companies.create', compact('TRNDetail'));
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
                    'company_name'
                );

        $rules = [
                    'company_name' => 'required'
                ];

        /*if( Input::get('trn_company_type') == 'Tax Group' )
        {
            $inputs = Input::only(
                'company_name',
                'company_work_designation',
                'company_contact_person',
                'company_mobile',

                'company_tax_number',
                'company_tax_date',
                'company_tax_period',
                'company_first_tax_period_start',
                'company_first_tax_period_end',
                'company_vat_certificate',
                'company_trade_license',
            );

            $rules = [
                'company_name' => 'required',
                'company_work_designation' =>   'required',
                'company_contact_person' =>   'required',
                'company_mobile' =>   'required',

                'company_tax_number' => 'required|unique:company_details|numeric|min:15',
                'company_tax_date' => 'required',
                'company_tax_period' =>   'required',
                'company_first_tax_period_start' =>   'required',
                'company_first_tax_period_end' =>  'required',
                'company_vat_certificate' => 'nullable|mimes:pdf',
                'company_trade_license' => 'nullable|mimes:pdf',
            ];
        }
        else if( Input::get('trn_company_type') == 'Single Company' )
        {
            $inputs = Input::only(
                'company_name',
                'company_work_designation',
                'company_contact_person',
                'company_mobile',
            );

            $rules = [
                'company_name' => 'required',
                'company_work_designation' =>   'required',
                'company_contact_person' =>   'required',
                'company_mobile' =>   'required',
            ];
        }*/


        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $CompanyDetail = new CompanyDetail;

        $CompanyDetail->trn_id = Input::get('trn_id');
        $CompanyDetail->company_name = Input::get('company_name');
        $CompanyDetail->company_work_designation = Input::get('company_work_designation');
        $CompanyDetail->company_contact_person = Input::get('company_contact_person');
        $CompanyDetail->company_mobile = Input::get('company_mobile');
        $CompanyDetail->company_phone = Input::get('company_phone');
        $CompanyDetail->company_address = Input::get('company_address');
        $CompanyDetail->company_city = Input::get('company_city');
        $CompanyDetail->company_state = Input::get('company_state');
        $CompanyDetail->company_country = Input::get('company_country');
        $CompanyDetail->company_zip_code = Input::get('company_zip_code');

        // company tax group insert data
        /*if( Input::get('trn_company_type') == 'Tax Group' )
        {
            $CompanyDetail->company_tax_number = Input::get('company_tax_number');
            $CompanyDetail->company_tax_date = Input::get('company_tax_date');
            $CompanyDetail->company_tax_period = Input::get('company_tax_period');
            $CompanyDetail->company_first_tax_period_start = Input::get('company_first_tax_period_start');
            $CompanyDetail->company_first_tax_period_end = Input::get('company_first_tax_period_end');

            // VAT Certificate
            if( Input::hasFile('company_vat_certificate') )
            {
                if( Input::file('company_vat_certificate')->isValid() )
                {
                    $destinationPath = 'public/uploads/documents'; // upload path

                    $extension = Input::file('company_vat_certificate')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'company_vat_certificate_' . rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('company_vat_certificate')->move($destinationPath, $fileName);

                    $CompanyDetail->company_vat_certificate = $fileName;
                }
            }

            // Trade License
            if( Input::hasFile('company_trade_license') )
            {
                if( Input::file('company_trade_license')->isValid() )
                {
                    $destinationPath = 'public/uploads/documents'; // upload path

                    $extension = Input::file('company_trade_license')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'company_trade_license_' . rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('company_trade_license')->move($destinationPath, $fileName);

                    $CompanyDetail->company_trade_license = $fileName;
                }
            }
        }*/

        $CompanyDetail->save();

        if( $CompanyDetail->comid > 0 )
        {
            // store history start
            $trnDtail = TRN::findOrFail($CompanyDetail->trn_id);

            $clientDtail = ClientDetail::findOrFail($trnDtail->client_detail_id);
            
            $activity = 'Tax Register Number '.$trnDtail->trn_tax_register_number.' new company added';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'New company created successfully.');

            return redirect::route('companies.index', ['id'=>$CompanyDetail->trn_id]);
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t create new company.');
            return redirect::back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyDetail  $companyDetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyDetail = CompanyDetail::findOrFail($id);

        $TrnDetail = TRN::findOrFail($companyDetail->trn_id);
        //dd($TrnDetail);

        return view('admin.companies.show', compact('companyDetail', 'TrnDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyDetail  $companyDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companyDetail = CompanyDetail::findOrFail($id);

        $TrnDetail = TRN::findOrFail($companyDetail->trn_id);

        // contract detail start
        $contractDetail = ContractDetail::
                            where('contract_id', $TrnDetail->contract_id)
                            ->first();

        if( $contractDetail->contract_end_date <= date('Y-m-d') )
        {
            return redirect::route('companies.index', ['id'=>$companyDetail->trn_id]);
        }
        //dd($contrantDetail);
        // contract detail end

        return view('admin.companies.edit', compact('companyDetail', 'TrnDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyDetail  $companyDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd(Input::All());
        $CompanyDetail = CompanyDetail::findOrFail($id);

        $inputs = Input::only(
            'company_name'
        );

        $rules = [
            'company_name' => 'required'
        ];
        
        /*if( Input::get('trn_company_type') == 'Single Company' )
        {
            $inputs = Input::only(
                'company_name',
                'company_work_designation',
                'company_contact_person',
                'company_mobile',
            );

            $rules = [
                'company_name' => 'required',
                'company_work_designation' =>   'required',
                'company_contact_person' =>   'required',
                'company_mobile' =>   'required',
            ];
        }
        else
        {
            $inputs = Input::only(
                'company_name',
                'company_work_designation',
                'company_contact_person',
                'company_mobile',

                'company_tax_number',
                'company_tax_date',
                'company_tax_period',
                'company_first_tax_period_start',
                'company_first_tax_period_end',
                'company_vat_certificate',
                'company_trade_license',
            );

            $rules = [
                'company_name' => 'required',
                'company_work_designation' =>   'required',
                'company_contact_person' =>   'required',
                'company_mobile' =>   'required',

                'company_tax_number' => ['required', 'numeric', 'min:15', Rule::unique('company_details')->ignore($CompanyDetail->comid, 'comid')],
                'company_tax_date' => 'required',
                'company_tax_period' =>   'required',
                'company_first_tax_period_start' =>   'required',
                'company_first_tax_period_end' =>  'required',
                'company_vat_certificate' => 'nullable|mimes:pdf',
                'company_trade_license' => 'nullable|mimes:pdf',
            ];
        }*/ 

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withErrors( $validator );
        }


        $CompanyDetail->company_name = Input::get('company_name');
        $CompanyDetail->company_work_designation = Input::get('company_work_designation');
        $CompanyDetail->company_contact_person = Input::get('company_contact_person');
        $CompanyDetail->company_mobile = Input::get('company_mobile');
        $CompanyDetail->company_phone = Input::get('company_phone');
        $CompanyDetail->company_address = Input::get('company_address');
        $CompanyDetail->company_city = Input::get('company_city');
        $CompanyDetail->company_state = Input::get('company_state');
        $CompanyDetail->company_country = Input::get('company_country');
        $CompanyDetail->company_zip_code = Input::get('company_zip_code');

        //company tax group insert data-
        /*if( Input::get('trn_company_type') == 'Tax Group' )
        {
            $CompanyDetail->company_tax_number = Input::get('company_tax_number');
            $CompanyDetail->company_tax_date = Input::get('company_tax_date');
            $CompanyDetail->company_tax_period = Input::get('company_tax_period');
            $CompanyDetail->company_first_tax_period_start = Input::get('company_first_tax_period_start');
            $CompanyDetail->company_first_tax_period_end = Input::get('company_first_tax_period_end');

            // VAT Certificate
            if( Input::hasFile('company_vat_certificate') )
            {
                if( Input::file('company_vat_certificate')->isValid() )
                {
                    $destinationPath = 'public/uploads/documents'; // upload path

                    $extension = Input::file('company_vat_certificate')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'company_vat_certificate_' . rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('company_vat_certificate')->move($destinationPath, $fileName);

                    $CompanyDetail->company_vat_certificate = $fileName;
                }
            }

            // Trade License
            if( Input::hasFile('company_trade_license') )
            {
                if( Input::file('company_trade_license')->isValid() )
                {
                    $destinationPath = 'public/uploads/documents'; // upload path

                    $extension = Input::file('company_trade_license')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'company_trade_license_' . rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('company_trade_license')->move($destinationPath, $fileName);

                    $CompanyDetail->company_trade_license = $fileName;
                }
            }
        }*/
        
        $CompanyDetail->save();

        if( $CompanyDetail->comid > 0 )
        {
            // store history start
            $trnDtail = TRN::findOrFail($CompanyDetail->trn_id);

            $clientDtail = ClientDetail::findOrFail($trnDtail->client_detail_id);
            
            $activity = 'Tax Register Number '.$trnDtail->trn_tax_register_number.' company updated';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Company updated successfully.');

            return redirect::route('companies.index', ['id'=>$CompanyDetail->trn_id]);
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t update company.');
            return redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyDetail  $companyDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyDetail $companyDetail)
    {
        //
    }
}
