<?php

namespace App\Http\Controllers;

use App\Actions\AssignManagersToUser;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeManagersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $result = Auth::user()->managers()->paginate(15);

        return response()->json(['managers' => new UserCollection($result)]);
    }
}
