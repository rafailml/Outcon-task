<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetManagerEmployees
{
    use AsAction;

    public function handle($request)
    {
        return Auth::user()->employees()
            ->when(array_key_exists('sort', $request), function ($query) use ($request) {
                $query->orderBy($request['sort'],
                    (array_key_exists('sort_desc', $request) ? 'DESC' : 'ASC'));
            })
            ->when(array_key_exists('search', $request), function ($query) use ($request) {

                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%')
                        ->orWhere('last_name', 'like', '%' . $request['search'] . '%')
                        ->orWhere('email', 'like', '%' . $request['search'] . '%');
                });

            })
            ->paginate($request['items']);
    }
}
