<?php

namespace App\Http\Controllers;

use App\Actions\GetManagerEmployees;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerEmployeesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(PaginationRequest $request)
    {
        $result = GetManagerEmployees::run($request->validated());

        return response()->json(['employees' => new UserCollection($result)]);
    }
}
