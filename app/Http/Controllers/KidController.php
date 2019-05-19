<?php

namespace App\Http\Controllers;

use App\Kid;
use Illuminate\Http\Request;
use App\Http\Requests\KidStoreRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kids = Kid::with(['user'])->get();

        return $this->successResponse([
            'data' => $kids
        ], Response::HTTP_OK);
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
    public function store(KidStoreRequest $request)
    {
        $data = $request->all();
        $data['avatar_link'] = $this->uploadAvatar();
        $newKid = Kid::create($data);
        $newKid->user = $newKid->user;

        return $this->successResponse([
            'data' => $newKid,
        ], Response::HTTP_CREATED);
    }

    private function uploadAvatar()
    {
        if (\request()->hasFile('avatar')) {
            $destination = '/images/avatar';
            $image = \request()->file('avatar');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path($destination);
            $image->move($destinationPath, $name);
            $uploadedLink = $destination . '/' . $name;

            return $uploadedLink;
        }

        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Kid $kid)
    {
        return $this->showOne($kid);
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
    public function update(Request $request, Kid $kid)
    {
        $data = $request->only([
            'name',
            'birth_day',
            'last_time_test',
            'gender',
        ]);
        if ($request->hasFile('avatar')) {
            $data['avatar_link'] = $this->uploadAvatar();
        }
        $kid->fill($data);
        $kid->save();
        $kid->user = $kid->user;

        return $this->successResponse([
            'data' => $kid,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kid = Kid::findOrFail($id);
        $kid->delete();
        return $this->successResponse('Data have been deleted', 200);
    }
}
