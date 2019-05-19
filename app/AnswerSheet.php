<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\AnswerSheetTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerSheet extends Model
{
    // use SoftDeletes;
    public $transformer = AnswerSheetTransformer::class;
    protected $table = 'answer_sheets';
    protected $fillable = [
        'test_result_id', 'question_id', 'answer_id',
    ];
}
