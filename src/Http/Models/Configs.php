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
            $options[$a[1]] = $a[0];
        }
        return $options;
    }

    // 获取所有配置文件
    public static function getAllConfigs() : array
    {
        $all = self::select('groups','key','value')->get();
        $grouped = $all->mapToGroups(function($item, $key){
            return [
                $item['groups'] => [
                    $item['key'] => $item['value']
                ]
            ];
        });
        return $grouped->toArray();
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
