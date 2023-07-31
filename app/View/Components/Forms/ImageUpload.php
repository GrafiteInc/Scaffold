<?php

namespace App\View\Components\Forms;

use App\View\Forms\ImageUploadForm;
use Illuminate\View\Component;

class ImageUpload extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return app(ImageUploadForm::class)->make()->render();
    }
}
