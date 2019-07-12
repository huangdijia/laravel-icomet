<?php

namespace Huangdijia\IComet\Console;

use Illuminate\Console\Command;
use Huangdijia\IComet\Facades\IComet;

class BroadcastCommand extends Command
{
    protected $signature   = 'icomet:broadcast {--content= : content}';
    protected $description = 'Broadcast channels';

    public function handle()
    {
        $content = $this->option('content') ?? '';

        if (!$content) {
            $this->error('content is empty!');
            return;
        }

        $resp  = IComet::broadcast($content);

        if (!$resp) {
            $this->warn('broadcast failure!');
            return;
        }

        $this->info('broadcast success!');
    }
}