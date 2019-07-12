# Laravel IComet

## Installation

~~~bash
composer require huangdijia/laravel-icomet
~~~

> Larvel

publish config

~~~bash
php artisan vendor:publish --provider="Huangdijia\IComet\ICometServiceProvider"
~~~

> Lumen

copy `icomet.php` file to config path

~~~bash
cp vendor/huangdijia/laravel-icomet/config/icomet.php config
~~~

register to `bootstrap/app.php`

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