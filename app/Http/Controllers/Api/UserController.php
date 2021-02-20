<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseApiController as BaseApiController;

class UserController extends BaseApiController
{
    public function show(Request $request, $userId)
    {
        $user = User::find($userId);

        if($user) {
//            return response()->json($user);
            return $this->sendResponse($user, 'User show successfully.');
        }
        return $this->sendError('User not found!.');

    }
}
