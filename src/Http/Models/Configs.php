<?php
namespace Dcat\Admin\Extension\Config\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    protected $table = "configs";

    protected $casts = [
        'rules' => 'json',
        'options' => 'json'
    ];

    public function config()
    {
        return $this->belongsTo(ConfigGroup::class, 'groups', 'g_key');
    }


    public function getOptionsAttribute($value)
    {
        if (empty($value)){
            return $value;
        }
        $arr = json_decode($value, true);
        if (is_string($arr)){
            $arr = explode(',', $arr);
        }
        $options = [];
        foreach ($arr as $item){
            $a = explode('|', $item);
            if (!isset($a[0]) || !isset($a[1])) continue;
            $options[$a[1]] = $a[0];
        }
        return $options;
    }

    // 获取所有配置文件
    public static function getAllConfigs() : array
    {
        $configs = self::all();
        $config_arr = [];
        foreach ($configs->toArray() as $config){
            $config_arr[$config['groups']][$config['key']] = $config['value'];
        }
        return $config_arr;
    }

    // 根据分组获取配置参数
    public static function getConfigByGroups($group) : array
    {
        if (is_string($group)){
            $group = self::where('groups', $group)->pluck('value', 'key');
        }elseif(is_array($group)){
            $group = self::whereIn('groups', $group)->pluck('value', 'key');
        }else{
            return [];
        }
        return $group;
    }

    // 获取单个配置参数
    public static function getConfig(string $key): array
    {
        $config = self::where('key', $key)->pluck('value', 'key');
        return $config;
    }
}
