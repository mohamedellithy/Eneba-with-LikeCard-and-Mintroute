<?php

namespace App\Console\Commands;

use App\Services\Eneba\Eneba;
use Illuminate\Console\Command;
use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Http;

class RefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:automation_refresh_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eneba  = new Eneba(false);
        $result = $eneba->refresh_token();
        if(isset($result['status']) && ($result['status'] == 200)){
            if(isset($result['refresh_token'])):
                ApplicationSetting::updateOrCreate([
                    'application' => 'eneba',
                    'name'        => 'refresh_token'
                ],[
                    'value'       => $eneba['refresh_token'] ?: null
                ]);
            endif;
    
            if(isset($result['access_token'])):
                ApplicationSetting::updateOrCreate([
                    'application' => 'eneba',
                    'name'        => 'access_token'
                ],[
                    'value'       => $eneba['access_token'] ?: null
                ]);
            endif;
        }
        return Command::SUCCESS;
    }
}
