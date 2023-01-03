<?php

namespace App\View\Components\Forms;

use App\Models\Team as TeamModel;
use Grafite\Forms\Fields\Text;
use Grafite\Forms\Forms\ComponentModelForm;
use Grafite\Forms\Fields\FileWithPreview;

class Team extends ComponentModelForm
{
    public $model = TeamModel::class;

    public $hasFiles = true;

    public $disableOnSubmit = true;

    public $deleteAsModal = true;

    public $routePrefix = 'teams';

    public $buttons = [
        'submit' => 'Save',
        'edit' => '<i class="fas fa-fw fa-cogs"></i> Settings',
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
        'edit' => 'btn btn-outline-primary btn-sm me-2',
        'delete' => 'btn btn-sm btn-outline-danger',
    ];

    // public $confirmMessage = 'Are you sure you want to delete this team?';

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
