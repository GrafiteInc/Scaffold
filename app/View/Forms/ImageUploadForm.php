<?php

namespace App\View\Forms;

use Grafite\Forms\Fields\Dropzone;
use Grafite\Forms\Forms\BaseForm;

class ImageUploadForm extends BaseForm
{
    public $route = 'ajax.files-upload';

    public $method = 'post';

    public $buttons = [];

    public function fields()
    {
        return [
            Dropzone::make('pics')
                ->unlabelled()
                ->option('route', $this->route),
        ];
    }
}
