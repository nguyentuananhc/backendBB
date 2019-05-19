<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\KidTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kid extends Model
{
    use SoftDeletes;

    protected $dates = ['birth_day', 'last_time_test', 'deleted_at'];
    public $transformer = KidTransformer::class;
    protected $appends = ['month_age', 'sex', 'age_to_string', 'last_test'];
    protected $table = 'kids';
    protected $fillable = [
        'name', 'avatar_link', 'birth_day', 'last_time_test', 'user_id', 'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setBirthDayAttribute($value)
    {
        $this->attributes['birth_day'] = Carbon::parse($value);
    }

    public function setLastTimeTestAttribute($value)
    {
        $this->attributes['last_time_test'] = Carbon::parse($value);
    }

    public function getSexAttribute($value)
    {
        $gender = $this->attributes['gender'] === 0 ? "Nữ" : "Nam";
        return $gender;
    }

    public function getMonthAgeAttribute($value)
    {
        $timeOffset = $this->{'birth_day'}->diff(Carbon::now());
        $yearTime = $timeOffset->y !== 0 ? $timeOffset->y * 12 : 0;
        $monthTime = $timeOffset->m;
        $totalMonth = $yearTime + $monthTime;
        return $totalMonth;
    }

    public function getAgeToStringAttribute($value)
    {
        $timeOffset = $this->{'birth_day'}->diff(Carbon::now());
        $yearString = $timeOffset->y !== 0 ? $timeOffset->y . ' tuổi ' : '';
        $monthString = $timeOffset->m !== 0 ? $timeOffset->m . ' tháng ' : '';
        $daystring = $timeOffset->d !== 0 ? $timeOffset->d . ' ngày' : '';
        $ageToString = $yearString . $monthString . $daystring;
        return $ageToString;
    }

    public function getLastTestAttribute($value)
    {
        if (!$this->{'last_time_test'}) {
            return null;
        }

        $timeOffset = $this->{'last_time_test'}->diff(Carbon::now());
        $yearTime = $timeOffset->y !== 0 ? $timeOffset->y * 12 : 0;
        $monthTime = $timeOffset->m;
        $lastTimeTest = $yearTime + $monthTime;
        return $lastTimeTest . ' tháng trước';
    }

    public function getAvatarLinkAttribute($value)
    {
        if ($value) {
            return env('APP_URL') . $value;
        }

        return null;
    }

    public function testResults()
    {
        return $this->hasMany('App\TestResult');
    }
}
