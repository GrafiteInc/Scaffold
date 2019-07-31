<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Schema;

trait Searchable
{
    public function search($payload)
    {
        $query = $this->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing($this->table);

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', "%{$payload}%");
        };

        return $query;
    }
}
