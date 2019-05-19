<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\AnswerTransformer;

class Answer extends Model
{
    public $transformer = AnswerTransformer::class;
    protected $table = 'answers';
    protected $fillable = [
        'content',
        'position',
        'question_id',
        'value',
        'is_special',
    ];

    protected $casts = [
        'is_special' => 'boolean',
    ];
}
