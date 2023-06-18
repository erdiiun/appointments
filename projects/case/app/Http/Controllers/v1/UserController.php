<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class UserController extends Controller
{
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userInfo()
    {
        return response()->json([
            'status' => 'success',
            'message' => auth()->user()
        ]);
    }

    /**
     * Update auth user info
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userUpdate(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {
            // Find auth user and update info
            $user = auth()->user();
            $user->name = $request->input('name');
            $user->password = $request->input('password');

            if ($user->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'The user has been updated successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while updating user'
                ]);
            }
        }catch (\Exception $e) {
            Log::channel('custom')->error('User update error');
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }
}
