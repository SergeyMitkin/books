<?php

namespace app\models;

use app\models\tables\Users;

class AdminLoginForm extends LoginForm
{
    public function rules()
    {
        $users = new Users();
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['username', 'in', 'range' => [$users->admin_name]],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
}