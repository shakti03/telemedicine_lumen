<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\GoToMeeting;
use App\Models\GoToToken;

use App\Services\GoToMeeting\GoToClient;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class RefreshGotoToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goto:token-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh GoTo-meeting token daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->goToClient = new GoToClient();
        $this->logger = Log::channel('gotomeeting');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->logger->debug('------- CRON : Get Access Token ----------');
        $tokenResponse = $this->goToClient->getRefreshToken();

        if ($tokenResponse) {
            $this->logger->info("------CRON : Token Refreshed ------");
        } else {
            $this->logger->info("------CRON : Token Failed ------");
        }
    }
}
