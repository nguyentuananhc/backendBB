<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TestResultTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestResult extends Model
{
    // use SoftDeletes;
    public $transformer = TestResultTransformer::class;
    protected $table = 'test_results';
    protected $fillable = [
        'total_scores', 'user_id', 'kid_id', 'level_id', 'part_id', 'test_evaluate_id', 'comments'
    ];

    public function answersheets()
    {
        return $this->hasMany('App\AnswerSheet');
    }
}
