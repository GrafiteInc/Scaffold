<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    public $primaryKey = 'id';

    public $timestamps = true;

    public $fillable = [
        'user_id',
        'relationship',
        'model_id',
        'email',
        'message',
        'token',
    ];

    public static $rules = [
        'relationship' => 'required',
        'model_id' => 'required',
        'email' => 'required',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFromAttribute()
    {
        return $this->user;
    }
}
