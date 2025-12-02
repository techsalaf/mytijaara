<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessSetting extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'key',
        'value',
        'name',
        'slug'
    ];
    public function storage()
    {
        return $this->morphMany(Storage::class, 'data');
    }
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('storage', function ($builder) {
            $builder->with('storage');
        });

    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
             Helpers::deleteCacheData('business_settings_all_data');
            $value = Helpers::getDisk();

            DB::table('storages')->updateOrInsert([
                'data_type' => get_class($model),
                'data_id' => $model->id,
            ], [
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        static::created(function ($item) {
            if (isset($item->name) && !empty($item->name)) {
                $item->slug = $item->generateSlug($item->name);
                $item->save();
            }
            Helpers::deleteCacheData('business_settings_all_data');
        });
        static::deleted(function(){
            Helpers::deleteCacheData('business_settings_all_data');
        });

        static::updated(function(){
            Helpers::deleteCacheData('business_settings_all_data');
        });

    }

    /**
     * Generate a unique slug for the business setting
     * 
     * @param string $name
     * @return string
     */
    private function generateSlug($name): string
    {
        $slug = Str::slug($name);
        if ($max_slug = static::where('slug', 'like', "{$slug}%")->latest('id')->value('slug')) {

            if ($max_slug == $slug) return "{$slug}-2";

            $max_slug = explode('-', $max_slug);
            $count = array_pop($max_slug);
            if (isset($count) && is_numeric($count)) {
                $max_slug[] = ++$count;
                return implode('-', $max_slug);
            }
        }
        return $slug;
    }

}
