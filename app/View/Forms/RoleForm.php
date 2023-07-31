<?php

namespace App\View\Forms;

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
        'edit' => '<span class="fas fa-fw fa-pencil-alt"></span> Edit',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $columns = 'sections';

    public $maxColumns = 2;

    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'edit' => 'btn btn-outline-primary btn-sm mr-2',
        'delete' => 'btn btn-outline-danger btn-sm ms-2',
    ];

    public $disableOnSubmit = true;

    public function setSections($fields)
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
                'required' => true,
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
                $options[$key][] = "permissions[{$model}.{$name}]";
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
                $options[] = Checkbox::make("permissions[{$model}.{$name}]", [
                    'label' => $label,
                ]);
            }
        }

        return $options;
    }
}
