@extends('admin/layouts/master')

@section('title', 'Dashboard')

@section('content')

  <div class="content-wrapper">
    
      <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
          <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-cube text-danger icon-lg"></i>
                </div>
                <div class="float-right">
                  <p class="mb-0 text-right">Total Revenue</p>
                  <div class="fluid-container">
                      <?php 
                      $FinanceRevenue = DB::table('contract_details')
                                        ->sum(DB::raw('contract_amount'));
                      ?>
                    <h3 class="font-weight-medium text-right mb-0">{{ $FinanceRevenue }}</h3>
                  </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Total Revenue
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
          <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-receipt text-warning icon-lg"></i>
                </div>
                <div class="float-right">
                  <p class="mb-0 text-right">Financial Year<br> Revenue</p>
                  <div class="fluid-container">
                    <?php 
                    $FinanceYearRevenue = DB::table('contract_details')
                                              ->whereYear('contract_start_date', date('Y'))
                                              ->sum(DB::raw('contract_amount'));
                      ?>
                    <h3 class="font-weight-medium text-right mb-0">{{ $FinanceYearRevenue }}</h3>
                  </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Financial Year Revenue
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
          <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-poll-box text-success icon-lg"></i>
                </div>
                <div class="float-right">
                    <?php 
                    $totalClientFinanceYear = DB::table('contract_details AS ConD')
                                ->join('contracts AS C', 'ConD.contract_id', '=', 'C.cid')
                                //->join('trns AS T', 'C.trn_id', '=', 'T.tid')
                                ->join('client_details AS CD', 'C.client_detail_id', '=', 'CD.cdid')
                                ->where('CD.client_status', 'Approved')
                                ->whereYear('ConD.contract_start_date', date('Y'))
                                ->distinct()
                                ->count(DB::raw('CD.user_id'));
                    ?>
                  <p class="mb-0 text-right">Financial Year<br> Clients</p>
                  <div class="fluid-container">
                    <h3 class="font-weight-medium text-right mb-0">{{ $totalClientFinanceYear }}</h3>
                  </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Financial Year Clients
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
          <div class="card card-statistics">
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left">
                  <i class="mdi mdi-account-location text-info icon-lg"></i>
                </div>
                <div class="float-right">
                    <?php 
                      $totalClient = DB::table('client_details')
                                          ->where('client_status', 'Approved')
                                          ->count(DB::raw('cdid'));
                    ?>
                  <p class="mb-0 text-right">Total Clients</p>
                  <div class="fluid-container">
                    <h3 class="font-weight-medium text-right mb-0">{{ $totalClient }}</h3>
                  </div>
                </div>
              </div>
              <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Total Clients
              </p>
            </div>
          </div>
        </div>
      </div>
      

      <div class="row">
        <div class="col-lg-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Clients Contract Expiry</h4>
              <div class="table-responsive">
                <table class="table table-bordered data-table">
                  <thead>
                    <tr>
                      <th>Email</th>
                      <th>Client Name</th>
                      <th>Client Number</th>
                      <th>Service Name</th>
                      <th>Amount (AED)</th>
                      <th>Expiry Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($Clients as $Client)

                      <?php
                      // client contract service
                      if( isset($Client->cid) )
                      {
                        $clientContractServices = DB::table('contract_services AS CS')
                            ->join('services AS S', 'CS.service_id', '=', 'S.sid')
                            ->where([
                                      ['contract_detail_id', '=', $Client->cd_id],
                                    ])
                            ->orderBy('S.service_name', 'ASC')
                            ->get();
                      }
                      ?>
                      <tr>
                        <td>{{ $Client->email }}</td>
                        <td>{{ $Client->client_name }}</td>
                        <td>{{ $Client->client_number }}</td>
                        <td>
                          <ul>
                            @forelse($clientContractServices as $clientContractService)
                              @if( isset($clientContractService->service_name) )
                                <li>
                                  {{ $clientContractService->service_name }}
                                </li>
                              @endif
                            @empty
                            @endforelse
                          </ul>
                        </td>
                        <td>{{ $Client->contract_amount }}</td>
                        <td>{{ Carbon\Carbon::parse($Client->contract_end_date)->format('F d, Y') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5">Data not found.</td>
                      <tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Current Month VAT Report List</h4>
              <div class="table-responsive">
                <table class="table table-bordered data-table">
                  <thead>
                    <tr>
                      <th data-orderable="false"><center>Status</center></th>
                      <th>Client Name</th>
                      <th>Client Number</th>
                      <th>Tax Register Number</th>
                      <th>Type of Tax Period</th>
                      <th>Tax Period</th>
                      <th>Submission Deadline</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse( $TrnDatas as $TrnData )
                      @php
                        $StartDate = $TrnData->trn_first_tax_period_start;

                        $EndDate = $TrnData->trn_first_tax_period_end;

                        // contract deatil start
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
                          // contract deatil end

                          // VAT Report Submitted start
                          $VATReportsSubmitted = array();
                          if( $contractDetail )
                          {
                              $vatReportsSubmit = DB::table('vat_report_submitted')
                                          ->where([
                                              ['contract_detail_id', $contractDetail->cd_id],
                                              ['trn_id', $contractDetail->tid],
                                              ['vat_report_tax_period', $contractDetail->trn_tax_period]
                                          ])
                                          ->get();

                              if( count($vatReportsSubmit) > 0 )
                              {
                                  foreach( $vatReportsSubmit as $vatReportSubmit )
                                  {
                                      $VATReportsSubmitted[] = $vatReportSubmit->vat_report_submission_deadline;
                                  }
                              }
                          }
                          // VAT Report Submitted end
                      @endphp

                      {{-- Monthly --}}
                      @if( $TrnData->trn_tax_period == 'Monthly' )
                        @php
                          $StartDate = date('Y-m-d', strtotime("+1 day",strtotime($EndDate)));

                          $EndDate = date ("Y-m-d", strtotime("+11 months", strtotime($StartDate)));
                        @endphp

                        @while( strtotime($StartDate) <= strtotime($EndDate) )

                          @php
                            $monthlyMonth = date("M Y", strtotime($StartDate));
                          @endphp

                          @if( $monthlyMonth == date('M Y'))
                            @php
                              $varReportDuration = date('d M, Y', strtotime($StartDate));

                              $vatReportSubmissionDeadline = date("28 M, Y", strtotime("-1 day", strtotime("+2 months", strtotime($StartDate))));
                            @endphp
                            <tr>
                              <td><center>
                                  @if( in_array($vatReportSubmissionDeadline, $VATReportsSubmitted) )
                                    <span class="btn-success p-2">Submitted</span>
                                  @else
                                    <span class="btn-danger p-2">Yet to Submit</span>
                                  @endif
                                </center></td>
                                <td>
                                  {{ $TrnData->client_name }}
                                </td>
                                <td>
                                  {{ $TrnData->client_number }}
                                </td>
                                <td>
                                  {{ $TrnData->trn_tax_register_number }}
                                </td>
                                <td>
                                  {{ $TrnData->trn_tax_period }}
                                </td>
                                <td>
                                  {{ $varReportDuration }}
                                </td>
                                <td>
                                  {{ $vatReportSubmissionDeadline }}
                                </td>
                              </tr>
                          @endif

                            @php
                              $StartDate = date ("Y-m-d", strtotime("+1 month", strtotime($StartDate)));
                            @endphp
                        @endwhile
                      @endif

                      {{-- Quarterly --}}
                      @if( $TrnData->trn_tax_period == 'Quarterly' )
                        @php
                          $StartDate = date('Y-m-d', strtotime("+1 day",strtotime($EndDate)));
                        
                          $EndDate = date ("Y-m-d", strtotime("+9 months", strtotime($StartDate)));
                        @endphp
                        
                        @while( strtotime($StartDate) <= strtotime($EndDate) )
                          @php
                            $quarterlyMonth = date("M Y", strtotime("-1 day", strtotime("+3 months", strtotime($StartDate))));
                          @endphp

                          @if( $quarterlyMonth == date('M Y'))
                            @php
                              $varReportDuration = date('d M, Y', strtotime($StartDate)).' to '.date("d M, Y", strtotime("-1 day", strtotime("+3 months", strtotime($StartDate))));

                              $vatReportSubmissionDeadline = date("28 M, Y", strtotime("-1 day", strtotime("+4 months", strtotime($StartDate))));
                            @endphp
                            <tr>
                              <td><center>
                                @if( in_array($vatReportSubmissionDeadline, $VATReportsSubmitted) )
                                  <span class="btn-success p-2">Submitted</span>
                                @else
                                  <span class="btn-danger p-2">Yet to Submit</span>
                                @endif
                              </center></td>
                              <td>
                                {{ $TrnData->client_name }}
                              </td>
                              <td>
                                {{ $TrnData->client_number }}
                              </td>
                              <td>
                                {{ $TrnData->trn_tax_register_number }}
                              </td>
                              
                              <td>
                                {{ $TrnData->trn_tax_period }}
                              </td>

                              <td>{{ $varReportDuration }}</td>

                              <td>{{ $vatReportSubmissionDeadline }}</td>
                              </tr>
                          @endif

                            @php
                              $StartDate = date ("Y-m-d", strtotime("+3 months", strtotime($StartDate)));
                            @endphp
                        @endwhile
                      @endif

                    @empty
                      <tr><td colspan="5">No data found.</td></tr>
                    @endforelse

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <?php /* <div class="row">
        <div class="col-lg-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">UnApproved User List</h4>
              <div class="table-responsive">
                <table class="table table-bordered data-table">
                  <thead>
                    <tr>
                      <th>Email</th>
                      <th>User Name</th>
                      <th>User Company</th>
                      <th data-oraderable="false">Status</th>
                      <th data-oraderable="false">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $userdata = array();
                        //$userdata = DB::table('users')->where('client_status','UnApproved')->get();    
                    ?>
                    @forelse($userdata as $userd)
                      <tr>
                        <td>{{ $userd->email }}</td>
                        <td>{{ $userd->user_firstname }}{{ $userd->user_lastname }}</td>
                        <td>{{ $userd->user_company  }}</td>
                        <td>
                            <form action="{{ route('userApproved', $userd->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <button type="submit" class="btn btn-info" >Approve</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('userDelete', $userd->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger" >Delete</button>
                            </form>
                           <!-- <a href ='{{route('userDelete', $userd->id) }}' class="btn btn-danger">Delete</a>-->
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5">Due date record not found.</td>
                      <tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> */ ?>

  </div>

@endsection