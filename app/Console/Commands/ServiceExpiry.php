<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\User;
use App\Service;
use App\ClientService;
//use App\Notification;

use Mail, DB;
//use App\Mail\ServiceExpiry as ServiceExpiryEmail;

class ServiceExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client service expiry notification send email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('contract_details AS ConD')
                    ->join('contracts AS C', function($join)
                        {
                            $join->on('ConD.contract_id', '=', 'C.cid');
                        })
                    ->join('client_details AS CD', function($join)
                        {
                            $join->on('C.client_detail_id', '=', 'CD.cdid');
                        })
                    ->join('users AS U', function($join)
                        {
                            $join->on('CD.user_id', '=', 'U.uid');
                        })
                    ->whereBetween('ConD.contract_end_date', [Carbon::now(), Carbon::now()->addDays(10)])
                    ->orderBy('ConD.contract_end_date', 'ASC')
                    ->get();


        if( count($users) )
        {
            foreach($users as $user)
            {
                $contractServices = DB::table('contract_services AS CS')
                            ->join('services AS S', 'CS.service_id', '=', 'S.sid')
                            ->select('S.service_name')
                            ->where([
                                      ['contract_detail_id', '=', $user->cd_id],
                                    ])
                            ->orderBy('S.service_name', 'ASC')
                            ->get();

                $contractServicesList = '';
                if( count($contractServices) > 0 )
                {
                    foreach( $contractServices as $contractService )
                    {
                        $contractServicesList .= $contractService->service_name.', ';
                    }
                }

                Mail::send('admin.clients.emails.service-expiry', 
                        [
                            'client_number' => $user->client_number,
                            'services_list' => $contractServicesList,
                            'contract_end_date' => $user->contract_end_date
                        ], 
                    function ($m) use ($user)
                    {
                        $m->from('dashboard@aladdinseo.com', 'Themmar');
                        $m->to($user->email, $user->client_number)
                            ->subject('Service Expiry Reminder!');
                    }
                );
            }
        }

        DB::table('tmp_cron')->delete();
        
    }
}