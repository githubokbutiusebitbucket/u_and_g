<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()
            ->json(compact('users'), Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        $user->groups()
            ->sync($validated['groups_id']);

        return response()->json([
            'message' => 'User Created',
            'user' => $user->fresh('groups'),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('groups')
            ->findOrFail($id);

        return response()
            ->json(compact('user'), Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'last_name' => 'string|min:1|max:256',
            'first_name' => 'string|min:1|max:256',
            'state' => 'boolean',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'confirmed',
            'groups_id' => 'nullable|array|exists:groups,id',
        ]);

        if (isset($validated['groups_id'])) {
            $user->groups()
                ->sync($validated['groups_id']);
        } else {
            $user->groups()
                ->detach();
        }

        if ($user->update($validated)) {
            return response()->json([
                'message' => 'User Updated',
                'user' => $user->fresh('groups'),
            ], Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
