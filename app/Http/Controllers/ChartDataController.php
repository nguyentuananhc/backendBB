<?php

namespace App\Http\Controllers;
use App\ChartData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ChartDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chartData = ChartData::all();
        return $this->success([
            'data' => $chartData
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LevelStoreRequest $request)
    {
        $newchartData = chartData::create($request->all());
        return $this->showOne($newchartData, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Level $chartData)
    {
        return $this->showOne($chartData);
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
    public function update(Request $request, ChartData $chartData)
    {
        //
        $chartData->fill($request->only([
            'level_id',
            'part_id',
            'max_value',
        ]));
        if ($chartData->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }
        $chartData->save();
        return $this->showOne($chartData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chartData = ChartData::findOrFail($id);
        $chartData->delete();
        return $this->successResponse('Data have been deleted', 200);
    }
}