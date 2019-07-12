<?php

namespace Huangdijia\IComet\Console;

use Illuminate\Console\Command;
use Huangdijia\IComet\Facades\IComet;

class CloseCommand extends Command
{
    protected $signature   = 'icomet:close {--cname= : cname}';
    protected $description = 'Close channel';

    public function handle()
    {
        $cname = $this->option('cname') ?? '';

        if (!$cname) {
            $this->error('cname is empty!');
            return;
        }

        $resp  = IComet::close($cname);

        if (!$resp) {
            $this->warn('close failure!');
            return;
        }

        $this->info('close success!');
    }
}