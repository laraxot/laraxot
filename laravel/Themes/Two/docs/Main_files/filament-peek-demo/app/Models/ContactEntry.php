<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEntry extends Model
{
    protected $fillable = [
        'email',
        'message',
        'first_name',
    ];
}
