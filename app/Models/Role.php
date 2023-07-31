<?php

namespace App\Models;

use App\View\Forms\RoleForm;
use Grafite\Forms\Traits\HasForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    use HasForm;

    public $form = RoleForm::class;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the permissions - and overide all in the case of `admin`.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getPermissionsAttribute($value)
    {
        if ($this->name === 'admin') {
            $options = [];
            $permissions = config('permissions', []);

            foreach ($permissions as $model => $action) {
                foreach ($action as $name => $label) {
                    $options[] = "{$model}.{$name}";
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
