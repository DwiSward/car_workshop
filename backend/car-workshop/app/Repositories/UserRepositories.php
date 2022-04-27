<?php

namespace App\Repositories;

use App\Models\User;


/**
 *  User repositories
 */
class UserRepositories
{

    // create user
    public function create($request, $customer)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->sourceable_id = $customer->id;
        $user->sourceable_type = 'App\Models\Customer';
        $user->save();
        return $user;
    }

    // update user
    public function update($request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return $user;
    }
}