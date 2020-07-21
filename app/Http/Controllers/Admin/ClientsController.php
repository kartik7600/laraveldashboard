<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\User;
use App\ClientDetail;
use App\Contract;
use App\ContractDetail;
use App\ContractService;
use App\TRN;

use App\Service;
use App\ClientUploadReport;

use App\Mail\AdminUserRegister;
use App\Http\Requests\ClientStoreRequest;
use App\History;

use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

use Validator, Redirect, Hash, DB, Session, Auth, File, Mail;

class ClientsController extends Controller
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
    public function index()
    {
        // client not show other client detail
        if( Auth::user()->user_role == 'client' )
        {
            return redirect::route('client.dashboard');
        }
        //dd(Input::All());

        $clientUsers = DB::table('users as U')
                            ->Join('client_details AS CD', 'U.uid', '=', 'CD.user_id');
                            //->Join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                            //->Join('trns AS T', 'C.cid', '=', 'T.contract_id');

        
        if( Input::get('search_tax_period') || Input::get('search_city') || Input::get('search_start_date') || Input::get('search_end_date') || Input::get('search_service_id') )
        {
            $clientUsers->Join('contracts AS C', 'CD.cdid', '=', 'C.client_detail_id')
                    /*->join(DB::raw('(SELECT contract_id FROM `trns` GROUP BY contract_id) AS T'), 
                        function($join)
                        {
                           $join->on('C.cid', '=', 'T.contract_id');
                        });*/
                        ->Join('trns AS T', 'C.cid', '=', 'T.contract_id');
        }

        // search tax period
        if( Input::get('search_tax_period') )
        {
            $clientUsers->where([
                                ['T.trn_tax_period', '=', Input::get('search_tax_period')]
                            ]);
        }

        // search manager
        if( Input::get('search_manager_id') )
        {
            $clientUsers->where([
                                    ['CD.manager_id', '=', Input::get('search_manager_id')],
                                ]);
        }

        // search service
        if( Input::get('search_service_id') )
        {
            $clientUsers->Join('contract_details AS ConD1', 'C.cid', '=', 'ConD1.contract_id')
                        ->Join('contract_services AS CS', 'ConD1.cd_id', '=', 'CS.contract_detail_id')
                        ->where([
                                ['CS.service_id', '=', Input::get('search_service_id')]
                            ]);
        }


        // search start and date date
        if( Input::get('search_start_date') && Input::get('search_end_date') )
        {
            $clientUsers->Join('contract_details AS ConD', 'C.cid', '=', 'ConD.contract_id')
                        ->where([
                                ['ConD.contract_start_date', '>=', Input::get('search_start_date')],
                                ['ConD.contract_end_date', '<=', Input::get('search_end_date')],
                            ]);
        }
        else
        {
            // search start date
            if( Input::get('search_start_date') )
            {
                $clientUsers->Join('contract_details AS ConD', 'C.cid', '=', 'ConD.contract_id')
                            ->where([
                                    ['ConD.contract_start_date', '>=', Input::get('search_start_date')],
                                ]);
            }

            // search start date
            if( Input::get('search_end_date') )
            {
                $clientUsers->Join('contract_details AS ConD', 'C.cid', '=', 'ConD.contract_id')
                            ->where([
                                    ['ConD.contract_end_date', '<=', Input::get('search_end_date')],
                                ]);
            }
        }

        // login with account manager
        if( Auth::user()->user_role == 'account_manager' )
        {
            $clientUsers->where([
                                    ['CD.manager_id', '=', Auth::user()->uid],
                                ]);
        }

        // search with city
        if( Input::get('search_city') )
        {
            $clientUsers->Join('company_details AS COM', 'T.tid', '=', 'COM.trn_id')
                        ->where([
                            ['COM.company_city', 'LIKE', '%'.Input::get('search_city').'%'],
                        ]);
        }


        $clientUsers = $clientUsers->where([
                                        ['CD.client_status', 'Approved'],
                                        ['U.user_role', 'client'],
                                    ])
                                    ->orderBy('U.user_first_name', 'ASC')
                                    ->get();
        //dd($clientUsers);

        // client manager
        $managers = User::where('user_role', 'account_manager')
                            ->orderBy('user_first_name', 'ASC')
                            ->pluck('user_first_name', 'uid');

        
        // services
        $services = Service::orderBy('service_name', 'ASC')
                            ->pluck('service_name', 'sid');

        return view('admin.clients.index', compact('clientUsers', 'managers', 'services') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*$services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');

        return view('admin.clients.create', compact('services'));*/

        return view('admin.clients.create');
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
            'email',
            'password',
            'confirmpassword',
            'user_first_name',
            'user_last_name',
            'user_mobile',
            //'user_phone',
            'user_profile_image',

            'client_number',
            'client_name',
            //'client_email',
        );

        $rules = [
            'email' => 'required|email|unique:users|max:190',
            'password'  =>  'required|min:8',
            'confirmpassword'   =>  'required|same:password',
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            'user_mobile' => 'nullable|required',
            //'user_phone' => 'nullable|numeric',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG',

            'client_number' => 'required|alpha_num|unique:client_details',
            'client_name' => 'required',
            //'client_email' => 'required|email|max:190',
        ];

        $validator = Validator::make( $inputs, $rules );

        //$validator = $request->validated();

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = new User;

        $user->email    = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        //$user->user_phone = Input::get('user_phone');
        //$user->user_address = Input::get('user_address');
        //$user->user_city = Input::get('user_city');
        //$user->user_state = Input::get('user_state');
        //$user->user_country = Input::get('user_country');
        //$user->user_zip_code = Input::get('user_zip_code');
        $user->remember_token = Input::get('_token');

        if( Input::get('btnSubmit') == 'Submit' )
        {
            $user->user_status = "Active";
        }      

        // profile image
        if( Input::hasFile('user_profile_image') )
        {
            if( Input::file('user_profile_image')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/profile_images'; // upload path

                $extension = Input::file('user_profile_image')->getClientOriginalExtension(); // getting image extension
                $fileName = 'profile_image_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('user_profile_image')->move($destinationPath, $fileName);
                $user->user_profile_image = $fileName;
            }
        }
        
        $user->save();

        if( $user->uid > 0 )
        {
            // Client Details
            $clientDetail = new ClientDetail;

            $clientDetail->user_id = $user->uid;
            $clientDetail->client_number = Input::get('client_number');
            $clientDetail->client_name = Input::get('client_name');
            //$clientDetail->client_email = Input::get('client_email');

            $activity = 'New client '.Input::get('client_number').' draft created';

            if( Input::get('btnSubmit') == 'Submit' )
            {
                $activity = 'New client '.Input::get('client_number').' created';

                $clientDetail->client_status = "Approved";
            }

            // store history start
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $user->uid,
                    'activity' => $activity
                ]);
            // store history end

            $clientDetail->save();

            if( Input::get('btnSubmit') == 'Submit' )
            {
                $user->password = Input::get('password'); // use send by email password

                Mail::to($user->email)->send(new AdminUserRegister($user));

                Session::flash('messageType', 'success');
                Session::flash('message', 'New client created successfully.');

                return redirect::route('clients.show', $user->uid);
            }
            else
            {
                Session::flash('messageType', 'info');
                Session::flash('message', 'New client draft created successfully.');

                return redirect::route('clients.draftedit', $user->uid);
            }
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t create new client.');
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
        // client not show other client detail
        if( Auth::user()->user_role == 'client' && Auth::user()->uid != $id )
        {
            return redirect::route('client.dashboard');
        }

        $userData = User::findOrFail($id);

        // Client Details
        $clientDetail = $userData->clientDetails;

        // manager detail
        $managerDetail = DB::table('users AS U')
                            ->join('client_details AS CD', 'U.uid', '=', 'CD.manager_id')
                            ->where([
                                    ['user_id', $id],
                                   ])
                            ->first();

        //contract detail
        $contractDetail = DB::table('contract_details AS ConD')
                        ->join('contracts AS C', 'ConD.contract_id', '=', 'C.cid')
                        //->join('client_details AS CD', 'C.client_detail_id', '=', 'C.cid')
                        ->where([
                                    ['C.client_detail_id', $clientDetail->cdid],
                                    //['CD.user_id', $userData->uid],
                                    //['ConD.contract_end_date', '>=', Carbon::now()],
                               ])
                        ->orderBy('ConD.contract_end_date', 'DESC')
                        ->first();
        //dd($contractDetail);


        // TRN Details
        $trnDetails = array();
        $contractServices = array();
        if( $contractDetail )
        {
            $trnDetails = TRN::where('contract_id', $contractDetail->cid)
                            ->get();

            // contract service
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
            //dd($contractServices);
        }
        //dd($trnDetails);

        return view('admin.clients.show', compact('userData', 'clientDetail', 'managerDetail', 'contractDetail', 'trnDetails', 'contractServices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataRow = User::findOrFail($id);

        // Client Details
        $clientDetail = $dataRow->clientDetails;

        // Account manager
        $managers = User::where([
                                    ['user_status', '=', 'Active'],
                                    ['user_role', '=', 'account_manager'],
                                ])
                                ->orderBy('user_first_name', 'ASC')
                                ->pluck('user_first_name', 'uid');

        return view('admin.clients.edit', compact('dataRow', 'clientDetail', 'managers'));
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
        $inputs = Input::only(
            'user_first_name',
            'user_last_name',
            'user_mobile',
            //'user_phone',
            'user_status',
            'user_profile_image',

            'client_name',
            //'client_email',
            
            'manager_id'
        );

        $rules = [
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            'user_mobile' => 'required',
            //'user_phone' => 'nullable|numeric',
            'user_status' => 'required',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG',

            'client_name' => 'required',
            //'client_email' => 'required',
            
            'manager_id' => 'required'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = User::findOrFail($id);

        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        //$user->user_phone = Input::get('user_phone');
        //$user->user_address = Input::get('user_address');
        //$user->user_city = Input::get('user_city');
        //$user->user_state = Input::get('user_state');
        //$user->user_country = Input::get('user_country');
        //$user->user_zip_code = Input::get('user_zip_code');
        $user->user_status = Input::get('user_status');
        $user->remember_token = Input::get('_token');
        
            if( Input::hasFile('user_profile_image') )
            {
                if( Input::file('user_profile_image')->isValid() )
                {
                    $destinationPath = 'public/uploads/profile_images'; // upload path

                    $oldImg = public_path('uploads/profile_images/'.$user->user_profile_image);
                    //dd($oldImg);
                    if( !empty($user->user_profile_image) && File::exists($oldImg) )
                    {
                        unlink($oldImg);
                    }

                    $extension = Input::file('user_profile_image')->getClientOriginalExtension(); // getting image extension
                    $fileName = 'profile_image-' . rand(11111, 99999) . '.' . $extension; // renameing image
                    Input::file('user_profile_image')->move($destinationPath, $fileName);
                    $user->user_profile_image = $fileName;
                }
            }

        $user->save();

        if( $user->uid > 0 )
        {
            // Client Details
            $clientDetail = ClientDetail::findOrFail(Input::get('client_detail_id'));

            $clientDetail->manager_id = Input::get('manager_id');
            $clientDetail->client_name = Input::get('client_name');
            //$clientDetail->client_email = Input::get('client_email');

            /*if( Input::get('btnSubmit') == 'Submit' )
            {
                $clientDetail->client_status = "Approved";
            }*/

            $clientDetail->save();

            // store history start
            $activity = 'Client '.$clientDetail->client_number.' updated';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $user->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Client updated successfully.');

            return redirect::route('clients.show', $user->uid);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t updated client.');
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


    /**
     * Display a draft listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function draft()
    {
        $clientUsers = DB::table('users as U')
                                ->Join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                                ->where([
                                    ['CD.client_status', '=', 'Draft'],
                                    ['U.user_role', '=', 'client'],
                                ])
                                ->orderBy('U.user_first_name', 'ASC')
                                ->get();

        return view('admin.clients.draft', compact('clientUsers') );
    }


    /**
     * Show the form for draft editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function draftEdit($id)
    {
        $dataRow = User::find($id);

        // Client Details
        $clientDetail = $dataRow->clientDetails;

        // contract
        //$clientContract = $dataRow->contracts->first();

        // contract services
        /*$contractServices = $clientContract->contractServices;

        $contractServicesSeleted = array();
        if( count($contractServices) > 0 )
        {
            foreach( $contractServices as $contractService )
            {
                $contractServicesSeleted[] = $contractService->service_id;
            }
        }

        $services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');

        return view('admin.clients.draft-edit', compact('dataRow', 'clientDetail', 'clientContract', 'contractServicesSeleted', 'services'));*/

        return view('admin.clients.draft-edit', compact('dataRow', 'clientDetail'));
    }

    /**
     * Draft Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDraftupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $inputs = Input::only(
            'email',
            'password',
            'confirmpassword',
            'user_first_name',
            'user_last_name',
            'user_mobile',
            //'user_phone',
            'user_profile_image',

            'client_number',
            'client_name',
            //'client_email',
        );

        $rules = [
            'email' => ['required', 'email', 'max:190', Rule::unique('users')->ignore($user->uid, 'uid')],
            'password'  =>  'required|min:8',
            'confirmpassword'   =>  'required|same:password',
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            'user_mobile' => 'required',
            //'user_phone' => 'nullable|numeric',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG',

            'client_number' => ['required', 'alpha_num', Rule::unique('client_details')->ignore(Input::get('client_detail_id'), 'cdid')],
            'client_name' => 'required',
            //'client_email' => 'required|email|max:190',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withErrors( $validator );
        }


        $user->email    = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        //$user->user_phone = Input::get('user_phone');
        //$user->user_address = Input::get('user_address');
        //$user->user_city = Input::get('user_city');
        //$user->user_state = Input::get('user_state');
        //$user->user_country = Input::get('user_country');
        //$user->user_zip_code = Input::get('user_zip_code');
        $user->remember_token = Input::get('_token');

        if( Input::get('btnSubmit') == 'Submit' )
        {
            $user->user_status = "Active";
        }      

        // profile image
        if( Input::hasFile('user_profile_image') )
        {
            if( Input::file('user_profile_image')->isValid() )
            {
                //$destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/profile_images'; // upload path

                $oldImg = public_path('uploads/profile_images/'.$user->user_profile_image);
                //dd($oldImg);
                if( !empty($user->user_profile_image) && File::exists($oldImg) )
                {
                    unlink($oldImg);
                }

                $extension = Input::file('user_profile_image')->getClientOriginalExtension(); // getting image extension
                $fileName = 'profile_image_' . rand(11111, 99999) . '.' . $extension; // renameing image
                Input::file('user_profile_image')->move($destinationPath, $fileName);
                $user->user_profile_image = $fileName;
            }
        }
        
        $user->save();

        if( $user->uid > 0 )
        {
            // Client Details
            $clientDetail = ClientDetail::findOrFail(Input::get('client_detail_id'));

            //$clientDetail->user_id = $user->uid;
            $clientDetail->client_number = Input::get('client_number');
            $clientDetail->client_name = Input::get('client_name');
            //$clientDetail->client_email = Input::get('client_email');

            $activity = 'Client '.Input::get('client_number').' draft updated';

            if( Input::get('btnSubmit') == 'Submit' )
            {
                $clientDetail->client_status = "Approved";

                $activity = 'New client '.Input::get('client_number').' created';
            }

            // store history start
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $user->uid,
                    'activity' => $activity
                ]);
            // store history end

            $clientDetail->save();

            if( Input::get('btnSubmit') == 'Submit' )
            {
                $user->password = Input::get('password'); // use send by email password

                Mail::to($user->email)->send(new AdminUserRegister($user));

                Session::flash('messageType', 'success');
                Session::flash('message', 'New client created successfully.');

                return redirect::route('clients.show', $user->uid);
            }
            else
            {
                Session::flash('messageType', 'info');
                Session::flash('message', 'Client draft updated successfully.');

                return redirect::back();
            }
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t update draft client.');
        return redirect::back();
    }


    /**
     * Display a listing of the search resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        dd(Input::All());

        $clientUsers = DB::table('users as U')
                                ->select('U.uid', 'U.user_profile_image', 'U.user_status', 'CD.manager_id', 'CD.client_number', 'CD.client_company')
                                ->Join('client_details AS CD', 'U.uid', '=', 'CD.user_id')
                                ->where([
                                    ['CD.client_status', '=', 'Approved'],
                                    ['U.user_role', '=', 'client'],
                                ]);

        

        if( Auth::user()->user_role == 'account_manager' )
        {
            $clientUsers->where([
                                    ['CD.manager_id', '=', Auth::user()->uid],
                                ]);
        }

        $clientUsers = $clientUsers ->orderBy('CD.client_company', 'ASC')
                                    ->get();

        return view('admin.clients.index', compact('clientUsers') );
    }
}