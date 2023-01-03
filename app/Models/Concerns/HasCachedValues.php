<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

trait HasCachedValues
{
    public function cacheIdentifier($string)
    {
        $id = $this->id;
        $class = str_replace('\\', '-', get_class($this));

        return strtolower("{$class}_{$string}_{$id}");
    }

    public function clearCachedValues()
    {
        collect($this->caches)->each(function ($cache) {
            Cache::forget($this->cacheIdentifier($cache));
        });
    }
}
