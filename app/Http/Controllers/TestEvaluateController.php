<?php

namespace App\Http\Controllers;
use App\TestEvaluate;
use Illuminate\Http\Request;
use App\Http\Requests\TestEvaluateStoreRequest;


class TestEvaluateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testevaluates = TestEvaluate::all();
        return $this->success([
            'data' => $testevaluates
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
    public function store(TestEvaluateStoreRequest $request)
    {
        $newTestEvaluate = TestEvaluate::create($request->all());
        return $this->showOne($newTestEvaluate, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TestEvaluate $testevaluate)
    {
        return $this->showOne($testevaluate);
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
    public function update(Request $request, $id)
    {   
        $testevaluate = TestEvaluate::findOrFail($id);
        $testevaluate->fill($request->only([
            'content',
            'level_id',
            'part_id',
            'min_score',
            'max_score',
        ]));
        $testevaluate->save();
        return $this->showOne($testevaluate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $testevaluate = TestEvaluate::findOrFail($id);
        $testevaluate->delete();
        return $this->successResponse('Data have been deleted', 200);
    }
}