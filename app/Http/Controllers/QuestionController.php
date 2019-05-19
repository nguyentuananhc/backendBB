<?php

namespace App\Http\Controllers;

use App\Question;
use App\Answer;
use DB;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\QuestionStoreRequest;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $levelId = $request->get('level_id');
        $questions = Question::with(['answers']);
        if ($levelId) {
            $questions = $questions->whereLevelId($levelId);
        }

        return $this->successResponse([
            'data' => $questions->get()
        ], \Illuminate\Http\Response::HTTP_ACCEPTED);
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
    public function store(QuestionStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['image_link'] = $this->uploadImage();
            $newQuestion = Question::create($data);
            $this->saveAnswers($newQuestion, $request->get('list_answers'));
            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            throw $e;
            DB::rollback();
        }

        if ($success) {
            $newQuestion->answers = $newQuestion->answers;
            return Response::json([
                'message' => 'success',
                'data' => $newQuestion,
            ], 201);
        }

        return Response::json([
            'message' => 'some errors occurred'
        ], 400);
    }

    private function uploadImage()
    {
        if (!\request()->hasFile('image')) {
            return null;
        }
        $destination = '/images/question';
        $image = \request()->file('image');
        $name = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path($destination);
        $image->move($destinationPath, $name);
        $uploadedLink = $destination . '/' . $name;

        return $uploadedLink;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return $this->showOne($question);
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
    public function update(Request $request, Question $question)
    {
        $data = $request->all();
        $data['image_link'] = $this->uploadImage();
        $question->fill($data);
        $question->save();

        if ($request['list_answers']) {
            DB::beginTransaction();
            try {
                Answer::where('question_id', '=', $question->id)->delete();
                $this->saveAnswers($question, $request->get('list_answers'));
                DB::commit();
                $success = true;
            } catch (\Exception $e) {
                $success = false;
                DB::rollback();
            }

            if ($success) {
                $question->answers = $question->answers;
                return Response::json([
                    'message' => 'update success',
                    'data' => $question,
                ], 201);
            }

            return Response::json([
                'message' => 'some errors occurred'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        DB::beginTransaction();
        try {
            Answer::where('question_id', '=', $question->id)->delete();
            $question->delete();
            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return Response::json([
                'message' => 'delete success'
            ], 201);
        }

        return Response::json([
            'message' => 'some errors occurred'
        ], 400);
    }

    private function saveAnswers($question, $answers)
    {
        $listAnswers = $answers;
        $answersModels = [];
        foreach ($listAnswers as $answer) {
            $answersModels[] = new Answer([
                'content' => $answer['content'],
                'value' => $answer['value'],
                'is_special' => $answer['is_special'],
            ]);
        }
        $question->answers()->saveMany($answersModels);
    }
}
