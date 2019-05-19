<?php
namespace App\Transformers;
use App\Level;
use League\Fractal\TransformerAbstract;
class LevelTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Level $level)
    {
        return [
            'id' => (int)$level->id,
            'from' => (int)$level->from,
            'to' => (int)$level->to,
            'is_basic' => (bool)$level->is_basic,
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'from' => 'from',
            'to' => 'to',
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
            'from' => 'from',
            'to' => 'to',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
