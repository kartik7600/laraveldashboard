<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ClientDetail;
use App\Contract;
use App\ContractDetail;
use App\ContractService;
use App\ContractInstallment;
use App\Service;
use App\History;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, DB, Session, Auth;

class ContractsController extends Controller
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

        $contractClientID = $request->client_detail_id;
        //dd($contractClientID);

        // client contract
        $clientContracts = DB::table('contracts as C')
                            ->Join('contract_details AS CD', 'C.cid', '=', 'CD.contract_id')
                            ->where([
                                        ['C.client_detail_id', '=', $contractClientID]
                                    ])
                            ->orderBy('CD.contract_end_date', 'DESC')
                            ->get();

        // dd($clientContracts);

        return view('admin.contract.index', compact('contractClientID', 'clientContracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $contractClientID = $request->id;
        
        /*$services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');*/
        $services = Service::orderBY('service_name', 'ASC')
                        ->get();
        //dd($services);

        return view('admin.contract.create', compact('contractClientID', 'services'));
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
            'contract_amount',
            'contract_start_date',
            'contract_end_date',
            'contract_services_id',
            'contract_total_installments',
            'installment_total_amount',

            //'contract_installment_amount[]',
            //'contract_installment_date[]'
        );

        $rules = [
            'contract_amount' => 'required|numeric',
            'contract_start_date' => 'required',
            'contract_end_date' => 'required',
            'contract_services_id' => 'required',
            'contract_total_installments' => 'required|integer|min:0',
            'installment_total_amount' => 'nullable|same:contract_amount',

            //'contract_installment_amount[]' => 'nullable|required',
            //'contract_installment_date[]' => 'nullable|required'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }


        $clientDetailID = Input::get('client_detail_id');

        $clientDtail = ClientDetail::findOrFail($clientDetailID);
        //dd($clientDtail);

        /*$contrantExists = Contract::
                            where('client_detail_id', $clientDetailID)
                            ->first();

        //dd($contrantExists['cid']);
        if( $contrantExists )
        {
            $contractID = $contrantExists['cid'];
        }
        else
        {*/
            $contract = new Contract;

            $contract->client_detail_id = $clientDetailID;
            $contract->save();

            $contractID = $contract->cid;
        //}

        if( $contractID > 0 )
        {
            // contract detail
            $contractDetail = new ContractDetail;

            $contractDetail->contract_id = $contractID;
            $contractDetail->contract_amount = Input::get('contract_amount');
            $contractDetail->contract_total_installments = Input::get('contract_total_installments');
            $contractDetail->contract_start_date = Input::get('contract_start_date');
            $contractDetail->contract_end_date = Input::get('contract_end_date');

            if( Input::get('contract_installments') !== NULL )
            {
                $contractDetail->contract_installments = 'Yes';
            }

            $contractDetail->save();

            if( $contractDetail->cd_id > 0 )
            {
                // contract service
                foreach( Input::get('contract_services_id') as $contract_services_id )
                {
                    $contractService = new ContractService;

                    $contractService->contract_detail_id = $contractDetail->cd_id;
                    $contractService->service_id = $contract_services_id;

                    $contractService->save();
                }

                // Contract Installment
                if( $contractDetail->contract_total_installments > 0 )
                {
                    foreach( Input::get('contract_installment_amount') as $Key => $Val )
                    {
                        if( $Val )
                        {
                            $contractInstallment = new ContractInstallment;

                            $contractInstallment->contract_detail_id = $contractDetail->cd_id;
                            $contractInstallment->contract_installment_amount = $Val;
                            $contractInstallment->contract_installment_date = Input::get('contract_installment_date')[$Key];

                            $contractInstallment->save();
                        }
                    }
                }
            }

            // store history start
            $activity = $clientDtail->client_number.' new contract created';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'New contract added successfully.');

            return redirect::route('contracts.index', ['client_detail_id'=>$clientDetailID]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t added new contract.');
        return redirect::back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contractDetail = ContractDetail::findOrFail($id);

        /*if( Auth::user()->user_role == 'client' && Auth::user()->uid != $contract->user_id )
        {
            return redirect::route('contracts.index', ['id'=>Auth::user()->uid]);
        }*/

        // contract installments
        $contractInstallments = $contractDetail->contractInstallments;

        return view('admin.contract.show', compact('contractDetail', 'contractInstallments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contractDetail = ContractDetail::findOrFail($id);

        if( $contractDetail->contract_end_date <= date('Y-m-d') )
        {
            $contrantExists = Contract::
                            where('cid', $contractDetail->contract_id)
                            ->first();
            //dd($contrantExists);

            return redirect::route('contracts.index', ['client_detail_id'=>$contrantExists->client_detail_id]);
        }
        //dd($contractDetail);

        // contract services
        $contractServices = $contractDetail->contractServices;
        //dd($contractServices);

        $contractServicesSeleted = array();
        if( count($contractServices) > 0 )
        {
            foreach( $contractServices as $contractService )
            {
                $contractServicesSeleted[] = $contractService->service_id;
            }
        }
        //dd($contractServicesSeleted);

        // contract installments
        $contractInstallments = $contractDetail->contractInstallments;
        //dd($contractInstallments);

        /*$services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');*/
        $services = Service::orderBY('service_name', 'ASC')
                        ->get();

        return view('admin.contract.edit', compact('contractDetail', 'contractServicesSeleted', 'services', 'contractInstallments'));
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
        //dd(Input::All());

        $inputs = Input::only(
            'contract_amount',
            'contract_start_date',
            'contract_end_date',
            'contract_services_id',
            'contract_total_installments',
            'installment_total_amount'
        );

        $rules = [
            'contract_amount' => 'required|numeric',
            'contract_start_date' => 'required',
            'contract_end_date' => 'required',
            'contract_services_id' => 'required',
            'contract_total_installments' => 'required|integer|min:0',
            'installment_total_amount' => 'nullable|same:contract_amount',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $contractDetail = ContractDetail::findOrFail($id);
        //dd($contractDetail['contract_id']);
        
        $contract = Contract::findOrFail($contractDetail['contract_id']);
        //dd($contract['client_detail_id']);
        
        $contractDetail->contract_amount = Input::get('contract_amount');
        $contractDetail->contract_total_installments = Input::get('contract_total_installments');
        $contractDetail->contract_start_date = Input::get('contract_start_date');
        $contractDetail->contract_end_date = Input::get('contract_end_date');

        if( Input::get('contract_installments') !== NULL )
        {
            $contractDetail->contract_installments = 'Yes';
        }
        else
        {
            $contractDetail->contract_installments = 'No';
        }

        $contractDetail->save();

        // client contract service
        if( $contractDetail->cd_id > 0 )
        {
            $deleted = ContractService::
                        where('contract_detail_id', '=', $contractDetail->cd_id)
                        ->delete();

            foreach( Input::get('contract_services_id') as $contract_services_id )
            {
                $contractService = new ContractService;

                $contractService->contract_detail_id = $contractDetail->cd_id;
                $contractService->service_id = $contract_services_id;

                $contractService->save();
            }


            // Contract Installment
            if( $contractDetail->contract_total_installments > 0 )
            {
                foreach( Input::get('contract_installment_amount') as $Key => $Val )
                {
                    if( $Val !== NULL)
                    {
                        //dd(Input::get('installment_id')[$Key]);
                        $installment_id = 0;
                        if( isset(Input::get('installment_id')[$Key]) )
                        {
                            $installment_id = Input::get('installment_id')[$Key];
                        }
                        //dd($installment_id);

                        if( $installment_id > 0 )
                        {
                            $contractInstallment = ContractInstallment::findOrFail($installment_id);
                        }
                        else
                        {
                            $contractInstallment = new ContractInstallment;
                        }

                        $contractInstallment->contract_detail_id = $contractDetail->cd_id;
                        $contractInstallment->contract_installment_amount = $Val;
                        $contractInstallment->contract_installment_date = Input::get('contract_installment_date')[$Key];

                        $contractInstallment->save();
                    }
                }
            }

            // store history start
            $clientDtail = ClientDetail::findOrFail($contract->client_detail_id);
            //dd($clientDtail);

            $activity = $clientDtail->client_number.' contract updated';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Contract updated successfully.');

            //return redirect::route('clients.show', $contract->user_id);
            //return redirect::route('contracts.index', ['client_detail_id'=>$clientDetailID]);
            return redirect::route('contracts.index', ['client_detail_id'=>$contract['client_detail_id']]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t updated contract.');
        return redirect::back();
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
