<?php

namespace Huangdijia\IComet\Console;

use Huangdijia\IComet\Facades\IComet;
use Illuminate\Console\Command;

class PsubCommand extends Command
{
    protected $signature   = 'icomet:psub';
    protected $description = 'Subscriber to comet-server\'s channel creation and deletion events, events are received as HTTP chunks.';

    public function handle()
    {
        try {
            IComet::psub(function ($channel, $status) {
                $this->info("{$channel} {$status}");
            });
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->warn('psub exited', 'v');
    }
}
