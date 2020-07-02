<?php

namespace Dcat\Admin\Extension\Config;

use Dcat\Admin\Extension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Config extends Extension
{
    const NAME = 'config';

    protected $serviceProvider = ConfigServiceProvider::class;

    protected $composer = __DIR__.'/../composer.json';

    protected $assets = __DIR__.'/../resources/assets';

    protected $views = __DIR__.'/../resources/views';

//    protected $lang = __DIR__.'/../resources/lang';

    protected $migrations = __DIR__.'/../database/migrations';


    public function import(Command $command)
    {
        parent::import($command);

        if (Schema::hasTable('configs') || Schema::hasTable('config_groups')){
            $command->error('数据库存在configs或者config_groups表，请删除数据表后重试');
            exit();
        }

        Artisan::call('migrate');

        $command->info('导入成功');

    }
}
