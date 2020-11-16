<?php

namespace App\Http\Forms;

use App\Models\Team;
use Grafite\Forms\Fields\FileWithPreview;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\ModelForm;

class TeamForm extends ModelForm
{
    public $model = Team::class;

    public $hasFiles = true;

    public $routePrefix = 'teams';

    public $buttons = [
        'submit' => 'Save',
        'delete' => '<span class="fas fa-fw fa-trash"></span> Delete',
    ];

    public $columns = 1;

    /**
     * Form button classes.
     *
     * @var array
     */
    public $buttonClasses = [
        'submit' => 'btn btn-primary',
        'cancel' => 'btn btn-secondary',
        'delete' => 'btn btn-sm btn-outline-danger',
    ];

    public function fields()
    {
        return [
            Text::make('name', [
                'required' => true,
            ]),
            FileWithPreview::make('avatar', [
                'preview_identifier' => '.avatar',
                'preview_as_background_image' => true,
            ]),
        ];
    }
}
