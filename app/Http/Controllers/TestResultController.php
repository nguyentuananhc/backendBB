<?php

namespace App\Http\Controllers;
use App\TestResult;
use App\TestEvaluate;
use App\AnswerSheet;
use App\Kid;
use DB;
use Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\TestResultStoreRequest;

class TestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testresults = TestResult::all();
        return $this->showAll($testresults);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestResultStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $totalScore = $request['total_scores'];
            $levelId = $request['level_id'];
            $partId = $request['part_id'];
            $testEvaluate = TestEvaluate::where([
                ['min_score', '<=', $totalScore],
                ['max_score', '>=', $totalScore],
                ['level_id', '=', $levelId ],
                ['part_id', '=', $partId],
            ])->first();
            $request['test_evaluate_id'] = $testEvaluate->id;
            $newTestResult = TestResult::create($request->all());

            $listAnswers = json_decode($request['list_answers']);
            $answerSheet = [];
            foreach ($listAnswers as $answer) {
                $answerSheet[] = new AnswerSheet([
                    'question_id' => $answer->{'question_id'},
                    'answer_id' => $answer->{'question_id'},
                    'test_result_id' => $newTestResult->id,
                ]);
            }
            $newTestResult->answersheets()->saveMany($answerSheet);
            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }

        if ($success) {
            return Response::json([
                'message' => 'Submit answer success'
            ], 201);
        }

        return Response::json([
            'message' => 'Some errors occurred'
        ], 400);
        // return $this->showOne($newTestResult, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TestResult $testresult)
    {
        return $this->showOne($testresult);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestResult $testresult)
    {
        //
        $testresult->fill($request->only([
            'total_scores',
            'user_id',
            'kid_id',
            'level_id',
            'part_id',
            'test_evaluate_id',
        ]));
        if ($testresult->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }
        $testresult->save();
        return $this->showOne($testresult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testresult = TestResult::findOrFail($id);
        $testresult->delete();
        return $this->successResponse('Data have been deleted', 200);
    }

    public function getTestResult(Request $request)
    {
        $userId = $request->id;
        if (!$userId) {
            return response()->json([
                'data' => [],
            ]);
        }

        $listKids = DB::table('kids')
        ->select('id', 'name')
        ->where('user_id', '=', $userId)
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
            $data[$kid->id]['basic'][] = $firstLevel;
            $data[$kid->id]['advance'][] = $firstLevel;
            if (!$listBasicLevels) {
                // $data[$kid->['id']]['basic'] = [];
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
}