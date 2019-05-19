<?php

namespace App;

use App\Transformers\QuestionTransformer;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $transformer = QuestionTransformer::class;
    protected $appends = ['image_url'];
    protected $table = 'questions';
    protected $fillable = [
        'position', 'content', 'part_id', 'level_id', 'special_comment', 'action_link', 'image_link',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->attributes['image_link']) {
            return null;
        }

        return env('APP_URL') . $this->attributes['image_link'];
    }
}
