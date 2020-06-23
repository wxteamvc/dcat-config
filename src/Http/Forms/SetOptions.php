<?php
namespace Dcat\Admin\Extension\Config\Http\Forms;

use Dcat\Admin\Extension\Config\Http\Models\ConfigGroup;
use Dcat\Admin\Extension\Config\Http\Models\Configs;
use Dcat\Admin\Widgets\Form;

class SetOptions extends Form
{

    protected $group;
    protected $configs;


    public function __construct(ConfigGroup $group, $data = [], $key = null)
    {
        parent::__construct($data, $key);
        $this->group = $group;
        $this->configs = $group->configs;
    }


    public function handle(array $input)
    {
        if (!isset($input['groups']) || empty($input['groups'])){
            return $this->error('分组参数不存在或者错误.');
        }
        $group = $input['groups'];
        $can_keys = Configs::where('groups', $group)->pluck('key');
        foreach ($can_keys as $key){
            $value = trim($input[$key]);
            Configs::where('key', $key)
                ->where('groups', $group)
                ->update(['value' => $value]);
        }
        return $this->success('设置成功.');

    }

    public function form()
    {
        $configs = $this->configs;
        foreach ($configs as $k => $config){
            $this->getWidget($config);
        }
        $this->hidden('groups')->value($this->group->g_key);

    }

    public function getWidget($config){
        switch ($config->widget){
            case "text":
                $widget = $this->text($config->key, $config->title);
                break;
            case "image":
                $widget = $this->image($config->key, $config->title)->url('/app_file/upload');
                break;
            case "file":
                $widget = $this->file($config->key, $config->title)->url('/app_file/upload');
                break;
            case "switch":
                $widget = $this->switch($config->key, $config->title);
                break;
            case "editor":
                $widget = $this->editor($config->key, $config->title);
                break;
            case "select":
                $widget = $this->select($config->key, $config->title)->options($config->options);
                break;
            case "textarea":
                $widget = $this->textarea($config->key, $config->title);
                break;
            case "radio":
                $widget = $this->radio($config->key, $config->title)->options($config->options);
                break;
            case "email":
                $widget = $this->email($config->key, $config->title);
                break;
            case "password":
                $widget = $this->password($config->key, $config->title);
                break;
            case "url":
                $widget = $this->url($config->key, $config->title);
                break;
            case "ip":
                $widget = $this->ip($config->key, $config->title);
                break;
            case "mobile":
                $widget = $this->mobile($config->key, $config->title);
                break;
            default:
                return false;
                break;
        }

        $widget->help($this->getHelp($config));
    }

    public function getHelp($config){
        if (!empty($config->tips)){
            return "Tips：{$config->tips}  <br/> 变量名：{ {$config->groups}.{$config->key} }";
        }else{
            return "变量名：{ {$config->groups}.{$config->key} }";
        }
    }

    public function default()
    {
        $configs = $this->configs;
        $default = [];
        foreach ($configs as $config){
            $default[$config->key] = $config->value;
        }
        return $default;
    }


}
