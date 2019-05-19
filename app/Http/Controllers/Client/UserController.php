<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Traits\UploadImageTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Kid;
use App\ChartData;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    use UploadImageTrait;

    public function login()
    {
        $user = User::find(4);
        $user->token = $user->createToken('User Token', ['client'])->accessToken;

        return $this->success($user);
    }

    public function updateSelectedKid(Request $request)
    {
        $user = Auth::user();
        $kidId = $request->{kid_id};
        if (!$user->id && !$kidId) {
            return response()->json([
                'message' => 'Id cannot be null',
            ]);
        }
        DB::table('users')
            ->where('id', '=', $user->id)
            ->update(['selected_kid_id' => $kidId]);
        return response()->json([
            'message' => 'Update selected kid success!',
        ]);
    }

    public function getTestResult(Request $request)
    {
        $userId = \Auth::user()->id;
        if (!$userId) {
            return response()->json([
                'data' => [],
            ]);
        }

        $listKids = Kid::where('user_id', '=', $userId)
            ->get()->toArray();

        $listParts = DB::table('parts')
            ->select('id', 'name')
            ->get()
            ->toArray();

        $listBasicLevels = DB::table('levels')
            ->select('id', 'from', 'to')
            ->where('is_basic', '=', 1)
            ->get()
            ->toArray();

        $listAdvanceLevels = DB::table('levels')
            ->select('id', 'from', 'to')
            ->where('is_basic', '=', 0)
            ->get()
            ->toArray();

        $data = array();

        foreach ($listKids as &$kid) {
            $firstLevel = new \stdClass;
            $firstLevel->name = '0 month';
            foreach ($listParts as &$part) {
                $firstLevel->{$part->name} = 0;
            }
            // dd($kid['id']);
            $data[$kid['id']]['basic'][] = $firstLevel;
            $data[$kid['id']]['advance'][] = $firstLevel;
            $data[$kid['id']]['kid_info'] = $kid;
            $data[$kid['id']]['comment'] = $this->getTheLastestComment($kid['id']);
            $data[$kid['id']]['last_test'] = $this->getTheLastestTime($kid['id']);
            // dd($data);
            if (!$listBasicLevels) {
                $data[$kid['id']]['basic'] = array();
            } else {
                foreach ($listBasicLevels as &$level) {
                    $levelObj = new \stdClass;
                    $levelObj->name = $level->from . " - " . $level->to . " months";
                    foreach ($listParts as &$part) {
                        $lastTestOfEachPart = DB::table('test_results')
                            ->select('test_results.*', 'parts.name as part_name')
                            ->join('levels', 'test_results.level_id', '=', 'levels.id')
                            ->join('parts', 'test_results.part_id', '=', 'parts.id')
                            ->where('is_basic', '=', 1)
                            ->where('kid_id', '=', $kid['id'])
                            ->where('level_id', '=', $level->id)
                            ->where('part_id', '=', $part->id)
                            ->orderBy('id', 'desc')
                            ->take(1)->first();
                        $maxChartScore = ChartData::where([
                            ['level_id', '=', $level->id],
                            ['part_id', '=', $part->id],
                        ])->value('max_value');
                        if ($lastTestOfEachPart) {
                            $partName = $lastTestOfEachPart->{'part_name'};
                            $currentScore = $lastTestOfEachPart->{'total_scores'};
                            $score = $lastTestOfEachPart->{'total_scores'};
                            // $maxScore = $lastTestOfEachPart->{'part_max_score'};
                            $score = round(($currentScore * 100) / $maxChartScore);
                            $levelObj->$partName = $score;
                        }
                    }
                    if (count((array)$levelObj) !== 1) $data[$kid['id']]['basic'][] = $levelObj;
                }
            }

            if (!$listAdvanceLevels) {
                $data[$kid['id']]['advance'] = array();
            } else {
                foreach ($listAdvanceLevels as &$level) {
                    $levelObj = new \stdClass;
                    $levelObj->name = $level->from . " - " . $level->to . " months";
                    foreach ($listParts as &$part) {
                        $lastTestOfEachPart = DB::table('test_results')
                            ->select('test_results.*', 'parts.name as part_name', 'parts.max_score as part_max_score')
                            ->join('levels', 'test_results.level_id', '=', 'levels.id')
                            ->join('parts', 'test_results.part_id', '=', 'parts.id')
                            ->where('kid_id', '=', $kid['id'])
                            ->where('is_basic', '=', 0)
                            ->where('level_id', '=', $level->id)
                            ->where('part_id', '=', $part->id)
                            ->orderBy('id', 'desc')
                            ->take(1)->first();
                        $maxChartScore = ChartData::where([
                            ['level_id', '=', $level->id],
                            ['part_id', '=', $part->id],
                        ])->value('max_value');
                        if ($lastTestOfEachPart) {
                            $partName = $lastTestOfEachPart->{'part_name'};
                            $currentScore = $lastTestOfEachPart->{'total_scores'};
                            $maxScore = $lastTestOfEachPart->{'part_max_score'};
                            // $score = round(($currentScore * 100) / $maxScore);
                            $score = round(($currentScore * 100) / $maxChartScore);
                            $levelObj->$partName = $score;
                        }
                    }
                    if (count((array)$levelObj) !== 1) $data[$kid['id']]['advance'][] = $levelObj;
                }
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getTheLastestComment($kidId)
    {
        $data = DB::table('test_results')->where('kid_id', '=', $kidId)->orderBy('level_id', 'desc')->take(1)->first();
        if (!$data) return [];
        $lastestLevelId = $data->{'level_id'};
        $listParts = DB::table('parts')
            ->select('id', 'name')
            ->get()
            ->toArray();
        $comment = [];
        foreach ($listParts as &$part) {
            $lastTestOfEachPart = DB::table('test_results')
                ->select('test_results.*', 'test_evaluates.content as comment')
                ->join('test_evaluates', 'test_results.test_evaluate_id', '=', 'test_evaluates.id')
                ->where('test_results.level_id', '=', $lastestLevelId)
                ->where('test_results.part_id', '=', $part->id)
                ->orderBy('id', 'desc')
                ->take(1)->first();
            if ($lastTestOfEachPart) {
                $newObj = new \stdClass;
                $newObj->name = $part->name;
                $newObj->mainComment = $lastTestOfEachPart->comment;
                $newObj->subComment = $lastTestOfEachPart->comments;
                $newObj->scores = $lastTestOfEachPart->total_scores;
                $comment[] = $newObj;
            }
        }
        // dd($comment);
        return $comment;
    }

    public function getTheLastestTime($kidId)
    {
        $data = DB::table('test_results')->where('kid_id', '=', $kidId)->orderBy('level_id', 'desc')->take(1)->first();
        if (!$data) return [];
        $lastestLevelId = $data->{'level_id'};
        $listParts = DB::table('parts')
            ->select('id', 'name')
            ->get()
            ->toArray();
        $time = [];
        foreach ($listParts as &$part) {
            $lastTestOfEachPart = DB::table('test_results')
                ->select('test_results.*', 'test_evaluates.content as comment')
                ->join('test_evaluates', 'test_results.test_evaluate_id', '=', 'test_evaluates.id')
                ->where('test_results.level_id', '=', $lastestLevelId)
                ->where('test_results.part_id', '=', $part->id)
                ->orderBy('id', 'desc')
                ->take(1)->first();
            if ($lastTestOfEachPart) {
                Carbon::setLocale('vi');
                $lastTime = Carbon::parse($lastTestOfEachPart->created_at);
                $newObj = new \stdClass;
                $newObj->time = $lastTime->diffForHumans();
                $time[] = $newObj;
            }
        }
        // dd($comment);
        return $time;
    }

    public function show()
    {
        $user = auth()->user();

        return $this->success($user);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'name' => 'string|max:191',
            'avatar' => 'image',
            'phone' => 'nullable|string|max:191',
            'address' => 'nullable|string|max:191',
            'birthday' => 'date',
        ]);
        $data = $request->all();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->upload('avatar', 'user');
        }
        $user->update($data);

        return $this->success($user);
    }
}
