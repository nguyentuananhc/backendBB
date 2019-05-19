<?php

namespace App\Http\Controllers;
use App\AnswerSheet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnswerSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answersheets = AnswerSheet::all();
        return $this->showAll($answersheets);
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
    public function store(Request $request)
    {
        $rules = [
            'test_result_id' => 'required|integer',
            'question_id' => 'required|integer',
            'answer_id' => 'required|integer',
        ];
        $this->validate($request, $rules);
        $newAnswerSheet = AnswerSheet::create($request->all());
        return $this->showOne($newAnswerSheet, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AnswerSheet $answersheet)
    {
        return $this->showOne($answersheet);
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
    public function update(Request $request, AnswerSheet $answersheet)
    {
        //
        $answersheet->fill($request->only([
            'test_result_id',
            'question_id',
            'answer_id',
        ]));
        if ($answersheet->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }
        $answersheet->save();
        return $this->showOne($answersheet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $answersheet = AnswerSheet::findOrFail($id);
        $answersheet->delete();
        return $this->successResponse('Data have been deleted', 200);
    }
}