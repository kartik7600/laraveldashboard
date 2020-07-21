<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\ClientContract;
use App\Service;
use App\ClientCotractService;
use App\History;

use App\Mail\AdminUserRegister;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, Hash, DB, Session, Auth, File, Mail;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.role')
                ->except('editprofile', 'postEditprofile', 'changepassword', 'postChangepassword');
    }

    public function create(Request $request)
    {
        $services = Service::orderBY('service_name', 'ASC')
                        ->pluck('service_name', 'sid');

        return view('admin.add-user', compact('services'));
    }

    public function postStore(Request $request)
    {
        //dd(Input::All());
        
        $inputs = Input::only(
            'email',
            'password',
            'confirmpassword',
            'user_first_name',
            'user_last_name',
            'user_mobile',
            'user_phone',
            'user_role',
            'user_profile_image'
        );

    	$rules = [
            'email' => 'required|email|unique:users|max:190',
            'password'	=>	'required|min:8',
            'confirmpassword'	=>	'required|same:password',
            'user_first_name'	=>	'required',
            'user_last_name'	=>	'required',
            'user_mobile' => 'nullable|numeric',
            'user_phone' => 'nullable|numeric',
            'user_role' => 'required',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = new User;

        if( Input::hasFile('user_profile_image') )
        {
            if( Input::file('user_profile_image')->isValid() )
            {
                $destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/'. $destinationFolder; // upload path

                $extension = Input::file('user_profile_image')->getClientOriginalExtension(); // getting image extension
                $fileName = 'profile_image_' . rand(11111, 99999) . '.' . $extension; // renameing image

                Input::file('user_profile_image')->move($destinationPath, $fileName);
                $user->user_profile_image = $fileName;
            }
        }

        $user->email    = Input::get('email');
        $user->password = Hash::make(Input::get('password'));
        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        $user->user_phone = Input::get('user_phone');
        $user->user_address = Input::get('user_address');
        $user->user_role = Input::get('user_role');
        $user->user_status = 'Active';
        $user->remember_token = Input::get('_token');

        $user->save();

        if( $user->uid > 0 )
        {
            $user->password = Input::get('password'); // use send by email password

            Mail::to($user->email)->send(new AdminUserRegister($user));

            // store history start
            $activity = 'New user created';
            
            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $user->uid,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'New user created successfully.');
            
            return redirect::route('a.userlist');
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t create new user.');
        return redirect::back();
    }

    /* User list */
    public function userlist()
    {
        $users = User::select('uid', 'email', 'user_first_name', 'user_profile_image', 'user_role', 'user_status')
                    ->where([
                            ['user_role', '!=', 'client']
                        ])
                    ->orderby('uid', 'DESC')
                    ->get();
                    
        return view( 'admin.user-list', compact('users') );
    }


    /* User edit */
    public function edit( $id )
    {
        $dataRow = User::findOrFail($id);

        return view('admin.edit-user', compact('dataRow'));
    }

    /* User update */
    public function postUpdate(Request $request, $id )
    {
        $inputs = Input::only(
            'user_first_name',
            'user_last_name',
            'user_mobile',
            'user_phone',
            'user_role',
            'user_status',
            'user_profile_image'
        );

        $rules = [
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            'user_mobile' => 'nullable|numeric',
            'user_phone' => 'nullable|numeric',
            'user_role' => 'required',
            'user_status' => 'required',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = User::find($id);

        if( Input::hasFile('user_profile_image') )
        {
            if( Input::file('user_profile_image')->isValid() )
            {
                $destinationFolder = 'profile_images'; // upload folder
                $destinationPath = 'public/uploads/'. $destinationFolder; // upload path

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

        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        $user->user_phone = Input::get('user_phone');
        $user->user_address = Input::get('user_address');
        $user->user_role = Input::get('user_role');
        $user->user_status = Input::get('user_status');
        $user->remember_token = Input::get('_token');

        $user->save();

        // store history start
        $activity = 'User updated';
        
        $history = History::create([
                'added_by_id' => Auth::user()->uid,
                'user_id' => $user->uid,
                'activity' => $activity
            ]);
        // store history end
        
        Session::flash('messageType', 'success');
        Session::flash('message', 'User updated successfully.');

        return redirect::route('a.userlist');
    }


    /* User profile */
    public function editprofile()
    {
        $dataRow = User::findOrFail(Auth::user()->uid);
        return view('admin.user-profile', compact('dataRow'));
    }

    /* User profile */
    public function postEditprofile(Request $request)
    {
        $inputs = Input::only(
            'user_first_name',
            'user_last_name',
            'user_mobile',
            'user_phone',
            'user_profile_image'
        );

        $rules = [
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            'user_mobile' => 'nullable|numeric',
            'user_phone' => 'nullable|numeric',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = User::find(Auth::user()->uid);

        if( Input::hasFile('user_profile_image') )
        {
            if( Input::file('user_profile_image')->isValid() )
            {
                $destinationFolder = 'profile_images'; // upload folder
                $destinationPath = public_path('uploads/'. $destinationFolder); // upload path

                $oldImg = public_path('uploads/profile_images/'.$user->user_profile_image);
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

        $user->user_first_name = Input::get('user_first_name');
        $user->user_last_name = Input::get('user_last_name');
        $user->user_dob = Input::get('user_dob');
        $user->user_mobile = Input::get('user_mobile');
        $user->user_phone = Input::get('user_phone');
        $user->user_address = Input::get('user_address');
        $user->remember_token = Input::get('_token');

        $user->save();

        Session::flash('messageType', 'success');
        Session::flash('message', 'Profile updated successfully.');

        return redirect::route('a.userprofile');
    }


    /* change password */
    public function changepassword()
    {
        return view('admin.change-password');
    }

    /* change password */
    public function postChangepassword(Request $request)
    {
        $inputs = Input::only(
            'current_password',
            'new_password',
            'new_confirm_password'
        );

        $rules = [
            'current_password' =>  'required',
            'new_password'  =>  'required|min:8',
            'new_confirm_password'  =>  'required|same:new_password'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = User::find(Auth::user()->uid);

        //dd($user);

        if( Hash::check(Input::get('current_password'), Auth::user()->password) )
        {
            $user->password = Hash::make(Input::get('new_password'));
            $user->remember_token = Input::get('_token');
            $user->save();

            Session::flash('messageType', 'success');
            Session::flash('message', 'Password changed successfully.');

            return redirect::route('a.changepassword');
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Please enter correct current password.');

            return redirect::route('a.changepassword');
        }
    }
}