<?php

namespace App\Http\Controllers;

use App\Part;
use Illuminate\Http\Request;
use App\Http\Requests\PartStoreRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parts = Part::all();
        return $this->success([
            'data' => $parts
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
     * @return string
     */

    private function uploadImage()
    {
        if (\request()->hasFile('image')) {
            $destination = '/images/part';
            $image = \request()->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path($destination);
            $image->move($destinationPath, $name);
            $uploadedLink = $destination . DIRECTORY_SEPARATOR . $name;

            return $uploadedLink;
        }

        return null;
    }

    public function store(PartStoreRequest $request)
    {
        $data = $request->all();
        $data['image'] = $this->uploadImage();
        $part = Part::create($data);

        return $this->success(['data' => $part], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Part $part)
    {
        return $this->success(['data' => $part]);
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
     * @param Part $part
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Part $part)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage();
        }
        $part->update($data);

        return $this->success(['data' => $part]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $part = Part::findOrFail($id);
        $part->delete();
        return $this->successResponse('Data have been deleted', 200);
    }
}
