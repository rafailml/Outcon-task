<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileImageUploadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ImageUploadRequest $request)
    {
        // save the file in storage
        $imagePath = $request->file('picture')->store('public/profile_pics');

        if (!$imagePath) {
            return response()->json(['error' => 'The file could not be saved.'], 500);
        }
        $imageName = str_replace("public/profile_pics/", "", $imagePath);
        $user = Auth::user();
        $user->profile_photo_path = $imageName;
        $user->save();

        return response()->json(['image' => $imageName]);
    }
}
