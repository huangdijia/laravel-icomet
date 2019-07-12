<?php

namespace Huangdijia\IComet\Console;

use Illuminate\Console\Command;
use Huangdijia\IComet\Facades\IComet;

class CheckCommand extends Command
{
    protected $signature   = 'icomet:check {--cname= : cname}';
    protected $description = 'Check channel';

    public function handle()
    {
        $cname = $this->option('cname') ?? '';

        if (!$cname) {
            $this->error('cname is empty!');
            return;
        }

        $resp  = IComet::check($cname);

        if (!$resp) {
            $this->warn($cname . ' channel disconnected!');
            return;
        }

        $this->info($cname . ' channel activing!');
    }
}