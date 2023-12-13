<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class myLaravelCommandPsh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:my-laravel-command-psh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ip = $this->getLocalIpforWindow();
        $this->call('serve', ['--host' => $ip]);

    }

    private function getLocalIpforWindow()
    {
        $ipInfo = shell_exec("ipconfig");
        preg_match('/Wireless LAN adapter Wi-Fi.*?IPv4 Address[\. ]*: ([\d\.]+)/s', $ipInfo, $matches);
        return $matches[1];         
    }

}
