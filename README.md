# Laravel IComet

## Installation

~~~bash
composer require huangdijia/laravel-icomet
~~~

> Lumen

copy config file to config path

~~~bash
cp vendor/huangdijia/laravel-icomet/config/config.php config
~~~

~~~php
$app->register(Huangdijia\IComet\ICometServiceProvider::class);
//...
$app->configure('icomet');
~~~

## Usage

~~~php
use Huangdijia\IComet\Facade\IComet;

// Sign
$resp = IComet::sign($name);

// Push
$resp = IComet::push($cname, $content);
~~~