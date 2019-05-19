<?php
namespace App\Transformers;
use App\Answer;
use League\Fractal\TransformerAbstract;
class AnswerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Answer $answer)
    {
        return [
            'id' => (int)$answer->id,
            'content' => (string)$answer->content,
            'position' => (string)$answer->position,
            'value' => (int)$answer->value,
            'question_id' => (int)$answer['question_id'],
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
            'value' => 'value',
            'question_id' => 'question_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}