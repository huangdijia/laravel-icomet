<?php

namespace Huangdijia\IComet\Console;

use Illuminate\Console\Command;
use Huangdijia\IComet\Facades\IComet;

class PushCommand extends Command
{
    protected $signature   = 'icomet:push {--cname= : channel name} {--content= : content}';
    protected $description = 'Push to channel';

    public function handle()
    {
        $cname   = $this->option('cname') ?? '';
        $content = $this->option('content') ?? '';

        if (!$cname) {
            $this->error('cname is empty!');
            return;
        }

        $resp  = IComet::push($cname, $content);

        if (!$resp) {
            $this->warn('push failure!');
            return;
        }

        $this->info('push success!');
    }
}