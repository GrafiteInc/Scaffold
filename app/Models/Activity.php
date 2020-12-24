<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'description',
        'request',
    ];

    public static $rules = [
        'request' => 'required',
    ];

    public $casts = [
        'request' => 'json',
    ];

    public function forModal()
    {
        return "<p>{$this->description}</p><hr><div class=\'border border-dark rounded\'>{$this->toHtml()}</div>";
    }

    public function toHtml()
    {
        return $this->jsonToTable($this->request);
    }

    public function jsonToTable($data)
    {
        $table = '<table class="table table-responsive table-borderless">';

        foreach ($data as $key => $value) {
            $table .= '<tr valign="top">';

            if (! is_numeric($key)) {
                $table .= '<td><strong>' . str_replace('_', ' ', Str::title($key)) . ':</strong></td><td>';
            } else {
                $table .= '<td colspan="2">';
            }

            if ((is_object($value) || is_array($value)) && ! empty($value)) {
                $table .= $this->jsonToTable($value);
            } elseif (is_bool($value)) {
                $table .= ($value) ? 'True' : 'False';
            } elseif (is_null($value) || empty($value)) {
                $table .= 'N/A';
            } else {
                $table .= $value;
            }

            $table .= '</td></tr>';
        }

        $table .= '</table>';

        return $table;
    }
}
