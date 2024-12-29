<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterUser $request)
    {
        $user = new User();

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password, [
                'rounds' => 12
            ]);
            $user->save();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'L\'utilisateur a été ajouté avec success',
                'data' => $user
            ]);
        } catch (Exception $e) {
            response()->json($e);
        }
    }
}
