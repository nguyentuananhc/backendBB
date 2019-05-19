<?php
namespace App\Transformers;
use App\Part;
use League\Fractal\TransformerAbstract;
class PartTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Part $part)
    {
        return [
            'id' => (int)$part->id,
            'name' => (string)$part->name,
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}