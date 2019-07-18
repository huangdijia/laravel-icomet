<?php

namespace Huangdijia\IComet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Icomet
 * @method array sign(string $cname = '', string $cb = '')
 * @method bool push($cname = '', $content = '')
 * @method bool batchPush(array $context =  [])
 * @method bool broadcast($content = '', array $cnames = null)
 * @method bool check(string $cname = '')
 * @method bool close(string $cname = '')
 * @method bool clear(string $cname = '')
 * @method array info(string $cname = '')
 * @method mixed psub()
 */
class IComet extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'icomet';
    }
}
