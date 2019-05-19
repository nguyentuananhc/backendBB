<?php

namespace App\Http\Controllers;

use App\ChartData;
use App\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\LevelStoreRequest;


class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Level::all();
        return $this->success([
            'data' => $levels
        ]);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LevelStoreRequest $request)
    {
        $newLevel = Level::create($request->all());
        return $this->showOne($newLevel, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        return $this->showOne($level);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        //
        $level->fill($request->only([
            'from',
            'to',
            'is_basic',
        ]));
        if ($level->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }
        $level->save();
        return $this->showOne($level);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();
        return $this->successResponse('Data have been deleted', 200);
    }

    public function getMaxScores($levelId)
    {
        $maxScores = ChartData::where('level_id', $levelId)->get();

        return $this->success([
            'data' => $maxScores,
        ]);
    }

    public function storeMaxScores(Request $request)
    {
        $maxScore = ChartData::where('level_id', $request->get('level_id'))
            ->where('part_id', $request->get('part_id'))->first();
        if ($maxScore) {
            return $this->success($maxScore);
        }
        $maxScore = ChartData::create($request->all());

        return $this->success([
            'data' => $maxScore,
        ]);
    }
}
