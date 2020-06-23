<?php

namespace Dcat\Admin\Extension\Config;

use Dcat\Admin\Extension;

class Config extends Extension
{
    const NAME = 'config';

    protected $serviceProvider = ConfigServiceProvider::class;

    protected $composer = __DIR__.'/../composer.json';

    protected $assets = __DIR__.'/../resources/assets';

    protected $views = __DIR__.'/../resources/views';

//    protected $lang = __DIR__.'/../resources/lang';

    protected $migrations = __DIR__.'/../database/migrations';

    protected $menu = [
        'title' => 'Config',
        'path'  => 'config',
        'icon'  => '',
    ];
}
