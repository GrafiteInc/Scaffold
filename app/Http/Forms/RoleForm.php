<?php

namespace App\Http\Forms;

use App\Models\Role;
use Illuminate\Support\Str;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Fields\Checkbox;
use Grafite\Forms\Forms\ModelForm;

class RoleForm extends ModelForm
{
    public $model = Role::class;

    public $routePrefix = 'admin.roles';

    public $buttons = [
        'submit' => 'Save',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete'
    ];

    public $columns = 'sections';

    public $maxColumns = 2;

    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'delete' => 'btn btn-outline-danger btn-sm',
    ];

    public function setSections()
    {
        return array_merge(
            [[
                'label',
            ]],
            $this->permissionOptionKeys()
        );
    }

    public function fields()
    {
        return array_merge([
            Text::make('label', [
                'required' => true
            ]),
        ], $this->permissionOptions());
    }

    public function permissionOptionKeys()
    {
        $options = [];
        $permissions = config('permissions');

        foreach ($permissions as $model => $action) {
            foreach ($action as $name => $label) {
                $key = Str::ucfirst(Str::singular($model)) . ' Permissions';
                $options[$key][] = "permissions[$model.$name]";
            }
        }

        return $options;

        return collect($options)->chunk(2)->toArray();
    }

    public function permissionOptions()
    {
        $options = [];
        $permissions = config('permissions');

        foreach ($permissions as $model => $action) {
            foreach ($action as $name => $label) {
                $options[] = Checkbox::make("permissions[$model.$name]", [
                    'label' => $label
                ]);
            }
        }

        return $options;
    }
}
