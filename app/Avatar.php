<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    // use SoftDeletes;
    protected $table = 'avatars';
    protected $fillable = [
        'name', 'path', 'kid_id',
    ];
}
