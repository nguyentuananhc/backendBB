<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UploadStoreRequest;
use Response;

class UploadController extends Controller
{
    public function fileUpload(UploadStoreRequest $request)
    {
        if ($request->hasFile('image')) {
            $destinationRequest = '/images/' . $request->destination;
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path($destinationRequest);
            $image->move($destinationPath, $name);
            return Response::json([
                'image_path' => $destinationRequest . '/' . $name
            ], 200);
        }
    }
}