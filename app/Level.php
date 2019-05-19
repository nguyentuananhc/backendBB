<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\LevelTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    // use SoftDeletes;
    public $transformer = LevelTransformer::class;
    protected $table = 'levels';
    protected $fillable = [
        'from', 'to', 'is_basic',
    ];
    protected $casts = [
        'is_basic' => 'boolean'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class)->with(['answers']);
    }

    public function chartsData()
    {
        return $this->hasMany(ChartData::class);
    }
}
