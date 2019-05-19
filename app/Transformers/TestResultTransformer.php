<?php
namespace App\Transformers;
use App\TestResult;
use League\Fractal\TransformerAbstract;
class TestResultTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(TestResult $testresult)
    {
        return [
            'id' => (int)$testresult->id,
            'total_scores' => (int)$testresult['total_scores'],
            'user_id' => (int)$testresult['user_id'],
            'level_id' => (int)$testresult['level_id'],
            'part_id' => (int)$testresult['part_id'],
            'test_evaluate_id' => (int)$testresult['test_evaluate_id'],
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'total_scores' => 'total_scores',
            'user_id' => 'user_id',
            'level_id' => 'level_id',
            'part_id' => 'part_id',
            'test_evaluate_id' => 'test_evaluate_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'total_scores' => 'total_scores',
            'user_id' => 'user_id',
            'level_id' => 'level_id',
            'part_id' => 'part_id',
            'test_evaluate_id' => 'test_evaluate_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}