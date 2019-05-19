<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TestEvaluateTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestEvaluate extends Model
{
    // use SoftDeletes;
    public $transformer = TestEvaluateTransformer::class;
    protected $table = 'test_evaluates';
    protected $fillable = [
        'content', 'level_id', 'part_id', 'min_score', 'max_score'
    ];
}
