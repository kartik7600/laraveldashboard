<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <div class="nav-link">
        {{-- <div class="user-wrapper">
          <div class="profile-image">
            {!! Html::image('public/admin/images/faces/face1.jpg', 'Profile image') !!}
          </div>
          <div class="text-wrapper">
            <p class="profile-name">{{ Auth::user()->user_firstname }}</p>
            <div>
              <small class="designation text-muted">{{ Auth::user()->user_role }}</small>
              <span class="status-indicator online"></span>
            </div>
          </div>
        </div> --}}

        @if( Gate::allows('admin-auth', Auth::user()) )
          <a href="{{ route('clients.create') }}"><button class="btn btn-success btn-block">New Client
            <i class="mdi mdi-plus"></i>
          </button></a>
        @endif
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('a.dashboard') }}">
        <i class="menu-icon mdi mdi-television"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    @if( Gate::allows('admin-auth', Auth::user()) )

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-user" aria-expanded="false" aria-controls="ui-user">
          <i class="menu-icon mdi mdi-account-outline"></i>
          <span class="menu-title">Users</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-user">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              
              <a class="nav-link" href="{{ route('a.userlist') }}">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('a.createuser') }}">Add User</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-client" aria-expanded="false" aria-controls="ui-client">
          <i class="menu-icon mdi mdi-account-outline"></i>
          <span class="menu-title">Clients</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-client">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              
              <a class="nav-link" href="{{ route('clients.index') }}">Clients</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('clients.draft') }}">Draft Clients</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="menu-icon mdi mdi-content-copy"></i>
          <span class="menu-title">Services</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              
              <a class="nav-link" href="{{ route('services.index') }}">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('services.create') }}">Add Service</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('histories.index') }}">
          <i class="menu-icon mdi mdi-history"></i>
          <span class="menu-title">History</span>
        </a>
      </li>
    @endif

    @if( Gate::allows('manager-auth', Auth::user()) )
      <li class="nav-item">
        <a class="nav-link" href="{{ route('clients.index') }}">
          <i class="menu-icon mdi mdi-account-outline"></i>
          <span class="menu-title">Clients</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('notes.index') }}">
          <i class="menu-icon mdi mdi-message"></i>
          <span class="menu-title">Notes</span>
        </a>
      </li>
      
    @endif

    @if( Gate::allows('client-auth', Auth::user()) )
      <li class="nav-item">
        <a class="nav-link" href="{{ route('clients.show', ['id'=>Auth::user()->uid]) }}">
          <i class="menu-icon mdi mdi-content-copy"></i>
          <span class="menu-title">Client</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="menu-icon mdi mdi-folder-upload"></i>
          <span class="menu-title">Uploaded Reports</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item">
              
              <a class="nav-link" href="{{ route('uploaded-reports.index') }}">List Uploaded Reports</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('uploaded-reports.create') }}">Upload Report</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="manager-detail ml-4 mt-5">
        @php
          // manager detail
          $managerDetail = DB::table('users AS U')
                            ->join('client_details AS CD', 'U.uid', '=', 'CD.manager_id')
                            ->where([
                                      ['user_id', Auth::user()->uid],
                                   ])
                            ->first();
        @endphp

        @if( $managerDetail )
          <h4>Manager Detail</h4>

          <span class="mt-1 mb-3">
            @if( $managerDetail->user_profile_image != '' )
              {!! Html::image('public/uploads/profile_images/'.$managerDetail->user_profile_image, 'Profile Image', array('class'=>'img-50 rounded-circle')) !!}
            @else
              {!! Html::image('public/uploads/default-profile-image.png', 'Profile Image', array('class'=>'img-50 rounded-circle')) !!}
            @endif
          </span>

          <span><b>Name: </b> {{ $managerDetail->user_first_name }} {{ $managerDetail->user_last_name }}</span>

          <span><b>Email: </b> <a href="{{ route('messages.create') }}">{{ $managerDetail->email }}</a></span>

          @if( $managerDetail->user_mobile )
            <span><b>Phone: </b> <a href="tel:{{ $managerDetail->user_mobile }}">{{ $managerDetail->user_mobile }}</a></span>
          @endif
        @endif
      </li>

    @endif



  </ul>
</nav>