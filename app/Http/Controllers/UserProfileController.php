<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        return response()->json(new UserResource(Auth::user()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->validated()['name'];
        $user->last_name = $request->validated()['last_name'];
        $user->email = $request->validated()['email'];
        $user->password = Hash::make($request->validated()['password']);
        $user->save();

        return response()->json('User profile edited');
    }


}
