<?php
namespace App\Transformers;
use App\Question;
use League\Fractal\TransformerAbstract;
class QuestionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Question $question)
    {
        return [
            'id' => (int)$question->id,
            'content' => (string)$question->content,
            'position' => (string)$question->position,
            'part_id' => (int)$question['part_id'],
            'level_id' => (int)$question['level_id'],
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'content' => 'content',
            'position' => 'position',
            'value' => 'value',
            'question_id' => 'question_id',
            'level_id' => 'level_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'content' => 'content',
            'position' => 'position',
            'part_id' => 'part_id',
            'level_id' => 'level_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}