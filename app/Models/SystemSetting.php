<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting value with fallback
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting !== null && $setting->value !== null ? $setting->value : $default;
    }

    /**
     * Set/Update a setting value
     */
    public static function set($key, $value, $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
