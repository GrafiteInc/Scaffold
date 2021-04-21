<?php

namespace App\Http\Forms;

use App\Models\Team;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\ModelForm;
use Grafite\Forms\Fields\FileWithPreview;

class TeamForm extends ModelForm
{
    public $model = Team::class;

    public $hasFiles = true;

    public $disableOnSubmit = true;

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

    public $confirmMessage = 'Are you sure you want to delete this team?';

    public $confirmMethod = 'confirmation';

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
