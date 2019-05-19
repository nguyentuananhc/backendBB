<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp;
use Illuminate\Http\Request;
use DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }

    public function getUserByToken(Request $request)
    {
        // Admin id = 1, Client id =4;
        $email = $request->email;
//        $user = User::select('name', 'is_admin', 'id')->where('email', '=', $email)->first();
        $user = User::find(1);
        // $user = User::find(4);
        if (!$user) {
            return response()->json([
                'message' => 'Get user information not successfully',
            ], Response::HTTP_BAD_REQUEST);
        }
        $token = $user['is_admin'] === 1 ? $this->createToken('admin', $user) : $this->createToken('client', $user);
        $customUserProperty = collect($user->toArray())->only(['email', 'name']);
        $customUserProperty['token'] = $token;
        $customUserProperty['role'] = $user['is_admin'] === 1 ? 'Admin' : 'Client';
        return response()->json([
            'message' => 'Get user informationin successfully',
            'data' => $customUserProperty,
        ]);
    }

    public function getUserInfoFromToken(Request $request)
    {
        $access_token = $request['access_token'];
        dd($access_token);
        $access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjhlMzcwN2YwNTJiZDJkNTljMWMwYTFmMTU0NjJhMDNiM2E0MmJlMjhhMTlmNTdlYjZjYTZjMjI0MzJkMDQzMjJmMjU4OTQ1YTVmZjFlZDhkIn0.eyJhdWQiOiIzIiwianRpIjoiOGUzNzA3ZjA1MmJkMmQ1OWMxYzBhMWYxNTQ2MmEwM2IzYTQyYmUyOGExOWY1N2ViNmNhNmMyMjQzMmQwNDMyMmYyNTg5NDVhNWZmMWVkOGQiLCJpYXQiOjE1Mzk1MzA1OTUsIm5iZiI6MTUzOTUzMDU5NSwiZXhwIjoxNTcxMDY2NTk1LCJzdWIiOiIxIiwic2NvcGVzIjpbImFkbWluIl19.FM0akgqpN8UZzBXQKujuKmIKesK6cJHOVXNKUxYCBAHov9wsOgHfX-ncVC0Sd-FDulxhzFW875efaUxNCW-Hy4_Ck_mVsY_w52tryleGrVZS1jf-tw7-WPrvabkpqSv5FAWjSJGOMNCjShc8VrOT6r5nuAAOvmhBKV7is0kXjLNDkrU4-EpJqrPTMS4nwEPnjKW4yAeADiPqEPSdoIM_iFczpEnG9p3xc4pJQoFGy1RETbaNkNE5RnLRnfW4VjT5nCLqf00k5KKAO73duclZp3W3EonJy-t-ee7Sp0MGuQzvlqHqLK-mI_qgXzaR5F4kYbeZ6IrE2pir94GWRYsVMR-B5MhsEvejkFeovDuOJ6jajL2aUOQcTyy29wQu190zFFSZmTN6_gpUEaZmrfFoabTzB1y310Dni9cH0M6oDc8N3LecRYL8ecYsI7TBI0Zi0h1f4ucgDioEPTuSJ22Cv7kvHQiyJ8_p2twtODCK4zGly_sWtft6SIylJZ3Ap5DNjnxs649zzVPRCBaBbNvov6zQiOp2t8fJc0UMERi0OF0JZxn3z8wPRXXw9onaKlrcsqIRgDLn2RDU6CB2yaLooo0Xt2bHp8fMI5PO-zNAIPRTI3u7uSUywATGKhOs01Ae2NVldNFXmk8SrjCs_fVjp8eLFy_N43JZXtgG7285Xxs';
        $token_parts = explode('.', $access_token);
        $token_header = $token_parts[0];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $user_token = $token_header_array['jti'];
        $user_id = DB::table('oauth_access_tokens')->where('id', $user_token)->value('user_id');
        $user = User::findOrFail($user_id);
        dd($user);
    }

    public function createToken($role, $user)
    {
        return $user->createToken('User Token', [$role])->accessToken;
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function findUser(Request $request)
    {
        $name = $request->get('name', '');
        $data = User::select('name', 'email', 'id')
            ->where('email', 'like', '%' . $name . '%')
            ->take(5)
            ->with('kids')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
