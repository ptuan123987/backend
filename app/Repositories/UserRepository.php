<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function findByEmail($email)
    {
        return $this->user->where('email', $email)->firstOrFail();
    }
    public function createUser($request) {
        $user = $this->user->create([
            'display_name'=> $request->display_name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        return $user;
    }


}
