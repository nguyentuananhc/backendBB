<?php
namespace App\Transformers;
use App\TestEvaluate;
use League\Fractal\TransformerAbstract;
class TestEvaluateTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(TestEvaluate $testevaluate)
    {
        return [
            'id' => (int)$testevaluate->id,
            'content' => (string)$testevaluate->content,
            'level_id' => (int)$testevaluate['level_id'],
            'part_id' => (int)$testevaluate['part_id'],
            'min_score' => (int)$testevaluate['min_score'],
            'max_score' => (int)$testevaluate['max_score'],
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'content' => 'content',
            'level_id' => 'level_id',
            'part_id' => 'part_id',
            'min_score' => 'min_score',
            'max_score' => 'max_score',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'content' => 'content',
            'level_id' => 'level_id',
            'part_id' => 'part_id',
            'min_score' => 'min_score',
            'max_score' => 'max_score',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}