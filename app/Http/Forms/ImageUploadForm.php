<?php

namespace App\Http\Forms;

use Grafite\Forms\Forms\BaseForm;
use Grafite\Forms\Fields\Dropzone;

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
