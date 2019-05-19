<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    public $transformer = UserTransformer::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'avatar',
        'birthday',
        'default_kid_id',
    ];

    protected $appends = ['avatar_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function kids()
    {
        return $this->hasMany(Kid::class);
    }

    public function getAvatarUrlAttribute()
    {
        if (!isset($this->attributes['avatar'])) {
            return null;
        }

        return env('APP_URL') . $this->attributes['avatar'];
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = Carbon::parse($value);
    }
}
