<?php

namespace Huangdijia\IComet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Huangdijia\IComet\Icomet
 * @method static array sign(string $cname = '', int $expires = 60)
 * @method static bool push($cname = '', $content = '')
 * @method static bool batchPush(array $context =  [])
 * @method static bool broadcast($content = '', array $cnames = null)
 * @method static bool check(string $cname = '')
 * @method static bool close(string $cname = '')
 * @method static bool clear(string $cname = '')
 * @method static array info(string $cname = '')
 * @method static void psub(Closure $callback)
 */
class IComet extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'icomet';
    }
}
