<?php

namespace Huangdijia\IComet\Console;

use Huangdijia\IComet\Facades\IComet;
use Illuminate\Console\Command;

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

        if (false !== strpos($cname, ',')) {
            $cnames = array_filter(explode(',', $cname));

            IComet::broadcast($content, $cnames);
        } else {
            $resp = IComet::push($cname, $content);

            if (!$resp) {
                $this->warn('push failure!');
                return;
            }
        }

        $this->info('push success!');
    }
}
