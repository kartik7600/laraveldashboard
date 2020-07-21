<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use App\User;
use App\Mail\ForgotPassword;
use Validator, Redirect, Hash, Auth, Session, DB, Mail;

class LoginController extends Controller
{
    public function login()
    {
    	return view('admin.login');
    }

    public function postLogin(Request $request)
    {
    	$credentials = $request->only('email', 'password');
        //$credentials = $request->()
        
    	if( Auth::attempt($credentials) )
    	{
            $User = Auth::getLastAttempted();

            if( $User->user_status == 'Inactive') {
                Auth::logout();
                Session::flash('error', 'This user is inactive.');
                return redirect()->back();
            }
            elseif( $User->client_status == 'UnApproved' ){
                Auth::logout();
                Session::flash('error', 'This user is UnApproved.');
                return redirect()->back();
            }
            

            if( $User->user_role == 'admin')
            {
                return redirect::route('a.dashboard');
            }
            elseif( $User->user_role == 'account_manager')
            {
                return redirect::route('manager.dashboard');
            }
            else
            {
                return redirect::route('client.dashboard');
            }
    	}

    	Session::flash('error', 'Incorrect username or password.');
    	return redirect::back();
    }

    /*public function register()
    {
    	return view('admin.register');
    }*/

    public function logout()
    {
    	Auth::logout();
    	return redirect()->route('a.login');
    }

    public function passwordEmail(){
        return view('admin.password-email');
    }

    public function postPasswordemail()
    {
        $email = Input::get('email');
        //dd($email);
        $user = User::select('uid', 'email', 'user_first_name')->where('email', $email)->first();
        
        //dd($user->email);
        if( !$user ) 
        {
            session()->flash('error', 'We can\'t find a user with that e-mail address.');
            return redirect()->back();
        }


        $token = str_random(30);
        $user->reset_password_token = $token;
        $user->save();

        Mail::to($user->email)->send(new ForgotPassword($user));

        session()->flash('success', ' Password has been sent to your registered email.');
        return redirect()->back();
    }

    public function passwordReset( $token )
    {
        return view('admin.password-reset', compact('token'));
    }

    public function postPasswordreset(Request $request, $token)
    {
        $inputs = Input::only(
            'password',
            'confirm_password'
        );

        $rules = [
            'password'  =>  'required|min:8',
            'confirm_password'   =>  'required|same:password'
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        $user = User::where('reset_password_token', $token)->first();

        if( !$user )
        {
            Session::flash('error', 'This token is not valid.');

            return redirect()->back();
        }

        $user->password = Hash::make(Input::get('password'));
        $user->reset_password_token = '';
        $user->save();

        Session::flash('success', 'Reset password successfully.');

        return redirect::route('a.login');
    }
}