<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserManageResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $pageNum = $request->input('pageNum', 1);
        $pageSize = $request->input('pageSize', 15);
        $users = User::paginate($pageSize,['*'],'page',$pageNum);
        return UserManageResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedUser = $request->validated();

        $user = User::create([
            'display_name' => $validatedUser['display_name'],
            'email' => $validatedUser['email'],
            'password' => bcrypt($validatedUser['password']),
            'role' => $validatedUser['role'],
        ]);

        return new UserManageResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        return new UserManageResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $userId)
    {
        $user = User::findOrFail($userId);

        if ($request->email !== $user->email && User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'Email is already taken'], 409);
        }

        $user->update([
            'display_name' => $request->display_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return new UserManageResource($user);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
