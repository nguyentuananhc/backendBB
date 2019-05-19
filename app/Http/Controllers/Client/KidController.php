<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UploadImageTrait;
use App\Kid;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;

class KidController extends Controller
{
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kids = \request()->user()->kids;

        return $this->success($kids);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:191',
            'gender' => Rule::in([0, 1]),
            'birth_day' => 'date',
        ]);
        $kid = $request->user()->kids()->create($request->all());
        $request->user()->update([
            'default_kid_id' => $kid->id,
        ]);

        return $this->success([
            'kid_info' => $kid,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'avatar' => 'image',
            //Will more validate
        ]);
        $data = $request->all();
        if ($request->hasFile('avatar')) {
            $data['avatar_link'] = $this->upload('avatar', 'kids');
        }
        $user = auth()->user();
        $kid = Kid::findOrFail($id);
        if ($kid->user_id != $user->id) {
            return $this->fail('access_denied');
        }
        $kid->update($data);

        $this->success($kid);
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
        $user = Auth::user();
        if($kid['user_id'] === $user->id || $user['is_admin'] === 1) {
            $kid->delete();
            return $this->success([]);
        }
        return response()->json([
            'message' => 'cannot delete this kid',
        ]);
    }
}
