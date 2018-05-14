<?php

namespace App\Repository;

class UserTransformer extends Transformer{
    public function transform($user){
        return [
            'fullname' => $user->name,
            'email' => $user->email,
            'remember_token' => $user->remember_token,
        ];
    }
}