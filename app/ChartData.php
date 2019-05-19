<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ChartData extends Model
{
    // use SoftDeletes;
    protected $table = 'chart_data';
    protected $fillable = [
        'level_id', 'part_id', 'max_value',
    ];
}
