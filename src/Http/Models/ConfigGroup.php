<?php
namespace Dcat\Admin\Extension\Config\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    protected $table = "config_groups";


    public function configs()
    {
        return $this->hasMany(Configs::class, 'groups', 'g_key');
    }

    public static function getAllGroup()
    {
        return self::pluck('g_name', 'g_key')->toArray();
    }

}
