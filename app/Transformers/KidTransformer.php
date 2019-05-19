<?php
namespace App\Transformers;
use App\Kid;
use League\Fractal\TransformerAbstract;
class KidTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Kid $kid)
    {
        return [
            'id' => (int)$kid->id,
            'name' => (string)$kid->name,
            'avatar_link' => (string)$kid['avatar_link'],
            'birth_day' => (string)$kid['birth_day'],
            'last_time_test' => (string)$kid['last_time_test'],
            'user_id' => (int)$kid['user_id'],
        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'avatar_link' => 'avatar_link',
            'birth_day' => 'birth_day',
            'last_time_test' => 'last_time_test',
            'user_id' => 'user_id',
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
            'name' => 'name',
            'avatar_link' => 'avatar_link',
            'birth_day' => 'birth_day',
            'last_time_test' => 'last_time_test',
            'user_id' => 'user_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}