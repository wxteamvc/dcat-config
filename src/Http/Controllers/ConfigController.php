<?php

namespace Dcat\Admin\Extension\Config\Http\Controllers;

use Dcat\Admin\Extension\Config\Http\Forms\SetOptions;
use Dcat\Admin\Extension\Config\Http\Models\ConfigGroup;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Tab;
use Illuminate\Routing\Controller;

class ConfigController extends Controller
{
    public function index(Content $content)
    {
        $tab = Tab::make();

        $groups = ConfigGroup::orderBy('sort','asc')->orderBy('id','asc')->get();
        foreach ($groups as $group){
            $tab->add($group->g_name, new SetOptions($group));
        }
        return $content->body($tab->withCard());
    }
}
