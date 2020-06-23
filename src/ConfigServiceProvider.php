<?php

namespace Dcat\Admin\Extension\Config;

use Dcat\Admin\Admin;
use Dcat\Admin\Extension\Config\Http\Models\Configs;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $extension = Config::make();

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, Config::NAME);
        }

        if ($lang = $extension->lang()) {
            $this->loadTranslationsFrom($lang, Config::NAME);
        }

        if ($migrations = $extension->migrations()) {
            $this->loadMigrationsFrom($migrations);
        }

        $this->app->booted(function () use ($extension) {
            $extension->routes(__DIR__.'/../routes/web.php');
        });

        $this->registerMenus();

        // 注入自定义配置信息
        $config = Configs::getAllConfigs();
        dump(config());
        dd($config);
    }


    protected function registerMenus()
    {
        Admin::menu()->add([
            [
                'id' => 1,
                'title'         => '配置管理',
                'icon'          => 'feather icon-settings',
                'uri'           => '',
                'parent_id'     => 0,
                'permission_id' => 'config', // 绑定权限
                'roles'         => [['slug' => 'gank']], // 绑定角色
            ],
            [
                'id' => 2,
                'title'         => '系统配置',
                'icon'          => 'feather icon-edit',
                'uri'           => 'setConfig',
                'parent_id'     => 1,
                'permission_id' => 'config', // 绑定权限
                'roles'         => [['slug' => 'gank']], // 绑定角色
            ],
            [
                'id' => 3,
                'title'         => '添加配置项',
                'icon'          => 'feather icon-plus',
                'uri'           => 'addConfigs',
                'parent_id'     => 1,
                'permission_id' => 'setConfig', // 绑定权限
                'roles'         => [['slug' => 'gank']], // 绑定角色
            ],
            [
                'id' => 4,
                'title'         => '添加分组项',
                'icon'          => 'feather icon-plus',
                'uri'           => 'addGroups',
                'parent_id'     => 1,
                'permission_id' => 'setConfig', // 绑定权限
                'roles'         => [['slug' => 'gank']], // 绑定角色
            ],
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}
