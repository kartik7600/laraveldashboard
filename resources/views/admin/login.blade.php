@extends('admin/layouts/master')

@section('title', 'Login')

@section('content')

  <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
    <div class="row w-100">
      <div class="col-lg-4 mx-auto">
        <div class="auto-form-wrapper">

          @if( Session::has('success') )
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
          @endif

          @if( Session::has('error') )
            <div class="alert alert-danger">
              {{ Session::get('error') }}
            </div>
          @endif

          {!! Form::open(array('route'=>'a.login')) !!}
            <div class="form-group">
              {!! Form::label('email', 'Username', array('class'=>'label')) !!}
              <div class="input-group">
                {!! Form::text('email', '', array('class'=>'form-control')) !!}
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="mdi mdi-check-circle-outline"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Password') !!}
              <div class="input-group">
                {!! Form::password('password', array('class'=>'form-control')) !!}
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="mdi mdi-check-circle-outline"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              {!! Form::submit('Login', array('class'=>'btn btn-primary submit-btn btn-block'))  !!}
            </div>
            
            <div class="form-group d-flex justify-content-between">
              <div class="form-check form-check-flat mt-0">
                <label class="form-check-label">
                  {!! Form::checkbox('remember-me', 'Y', true, array('class'=>'form-check-input')) !!}
                  Keep me signed in
                </label>
              </div>
              <a href="{{ route('a.passemail') }}" class="text-small forgot-password text-black">Forgot Password</a>
            </div>
            
             
            {{-- <div class="text-block text-center my-3">
              <span class="text-small font-weight-semibold">Not a member ?</span>
              <a href="{{ route('a.register') }}" class="text-black text-small">Create new account</a>
            </div> --}} 
          {!! Form::close() !!}
        </div>
        <ul class="auth-footer">
          <li>
            <a href="#">Conditions</a>
          </li>
          <li>
            <a href="#">Help</a>
          </li>
          <li>
            <a href="#">Terms</a>
          </li>
        </ul>
        <p class="footer-text text-center">copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
      </div>
    </div>
  </div>

@endsection