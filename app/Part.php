<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\PartTransformer;

class Part extends Model
{
    //
    public $transformer = PartTransformer::class;
    protected $table = 'parts';
    protected $appends = ['image_url'];
    protected $fillable = [
        'name',
        'content',
        'max_score',
        'image',
    ];

    public function getImageUrlAttribute()
    {
        return $this->attributes['image'] ? env('APP_URL') . $this->attributes['image'] : null;
    }
}
