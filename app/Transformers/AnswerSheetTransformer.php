<?php
namespace App\Transformers;
use App\AnswerSheet;
use League\Fractal\TransformerAbstract;
class AnswerSheetTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(AnswerSheet $answersheet)
    {
        return [
            'id' => (int)$answersheet->id,
            'test_result_id' => (int)$answersheet['total_scores'],
            'question_id' => (int)$answersheet['user_id'],
            'answer_id' => (int)$answersheet['level_id'],
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'test_result_id' => 'test_result_id',
            'question_id' => 'question_id',
            'answer_id' => 'answer_id',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'test_result_id' => 'test_result_id',
            'question_id' => 'question_id',
            'answer_id' => 'answer_id',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}