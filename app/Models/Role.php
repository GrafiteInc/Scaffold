<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    /**
     * Fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
        'permissions',
    ];

    /**
     * Field casts.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'json',
    ];

    /**
     * Rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:roles',
        'label' => 'required',
    ];

    /**
     * A Role's users.
     *
     * @return Relationship
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the permissions - and overide all in the case of `admin`.
     *
     * @param string $value
     * @return array
     */
    public function getPermissionsAttribute($value)
    {
        if ($this->name === 'admin') {
            $options = [];
            $permissions = config('permissions');

            foreach ($permissions as $model => $action) {
                foreach ($action as $name => $label) {
                    $options[] = "${model}.${name}";
                }
            }

            return $options;
        }

        if (is_null($value)) {
            return [];
        }

        return json_decode($value);
    }
}
