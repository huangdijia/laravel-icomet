<?php

namespace Huangdijia\IComet\Console;

use Illuminate\Console\Command;
use Huangdijia\IComet\Facades\IComet;

class InfoCommand extends Command
{
    protected $signature   = 'icomet:info {--C|cname= : channel name}';
    protected $description = 'Get info of comet-server or one channel';

    public function handle()
    {
        $cname = $this->option('cname') ?? '';
        $resp  = IComet::info($cname);

        $this->table(array_keys($resp), [array_values($resp)]);
    }
}