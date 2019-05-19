<?php

namespace App\Http\Controllers\Client;

use App\AnswerSheet;
use App\Http\Controllers\Controller;
use App\Kid;
use App\Level;
use App\TestEvaluate;
use App\TestResult;
use App\Part;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kidId = $request->get('kid_id');
        $userId = \Auth::user()->id;
        $kid = Kid::findOrFail($kidId);
        if ($kid->user_id !== $userId) {
            return response()->json([
                'message' => 'This kid not belong to User !',
            ]);
        }
        $bD = $kid->birth_day;
        $timeOffset = $bD->diff(Carbon::now());
        $yearTime = $timeOffset->y !== 0 ? $timeOffset->y * 12 : 0;
        $monthTime = $timeOffset->m;
        $totalMonth = $yearTime + $monthTime;
        $days = $timeOffset->d;
        if ($days > 15) $totalMonth += 1;
        if ($totalMonth == 0 && $days < 16) {
            return response()->json([
                'message' => 'No Test data for this kid !',
            ]);
        }

        $level = Level::with(['questions'])
            ->where('from', '=', $totalMonth)
            ->where('to', '>', $totalMonth)
            ->get();
        return $this->success($level);
    }


    private function findEvaluate($item)
    {
        return TestEvaluate::where([
            ['min_score', '<=', $item['total_scores']],
            ['max_score', '>=', $item['total_scores']],
            ['level_id', '=', $item['level_id']],
            ['part_id', '=', $item['part_id']],
        ])->first();
    }

    private function createResult($item, $testEvaluate)
    {
        $kidId = \request()->get('kid_id');
        $testResult = TestResult::create([
            'total_scores' => $item['total_scores'],
            'user_id' => \request()->user()->id,
            'kid_id' => $kidId,
            'level_id' => $item['level_id'],
            'part_id' => $item['part_id'],
            'comments' => $item['comments'],
            'test_evaluate_id' => $testEvaluate ? $testEvaluate->id : 0,
        ]);

        return $testResult;
    }

    private function createAnswers($item, $testResult)
    {
        $answers = [];
        foreach ($item['answers'] as $ans) {
            $answers[] = new AnswerSheet([
                'question_id' => $ans['question_id'],
                'answer_id' => $ans['question_id'],
                'test_result_id' => $testResult->id,
            ]);
        }
        $testResult->answersheets()->saveMany($answers);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $success = false;
        $data = $request->get('data');
        DB::beginTransaction();
        $rs = [];
        foreach ($data as $item) {
            $partObj = new \stdClass;
            $testEvaluate = $this->findEvaluate($item);
            $partObj->name = Part::findOrFail($item['part_id'])->name;
            $partObj->scores = $item['total_scores'];
            $partObj->mainCmt = $testEvaluate->content;
            $partObj->subCmt = $item['comments'];
            $rs[] = $partObj;
        }
        try {
            foreach ($data as $item) {
                $testEvaluate = $this->findEvaluate($item);
                $testResult = $this->createResult($item, $testEvaluate);
                $partObj = new \stdClass;
                $this->createAnswers($item, $testResult);
                DB::commit();
                $success = true;
            }
        } catch (\Exception $e) {
            throw $e;
            $success = false;
            DB::rollback();
        }

        if ($success) {
            return $this->success($rs, Response::HTTP_CREATED);
        }

        return $this->fail('Some error happen!');
    }
}
