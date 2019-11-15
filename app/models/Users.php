<?php

namespace App\Models;

use Igoframework\Core\Base\Model;

class Users extends Model
{
    protected $table = 'users';

    public $attributes = [
        'login' => '',
        'email' => '',
        'name' => '',
        'password' => '',
    ];

    public $rules = [
        'required' => [
            ['login'],
            ['email'],
            ['name'],
            ['password'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6]
        ],
    ];

    public function checkUnique()
    {
        $user = $this->findOneWhere([
            'login' => $this->attributes['login'],
            'email' => $this->attributes['email'],
        ], 'OR');

        if ($user) {
            if ($user['login'] == $this->attributes['login']) {
                $this->errors['unique'][] = 'Такой логин уже занят';
            }
            if ($user['email'] == $this->attributes['email']) {
                $this->errors['unique'][] = 'Такой email уже занят';
            }
            return false;
        }
        return true;
    }

    public function login($data)
    {
        
// dd([$login, $password], 0);
        $this->rules = [
            'required' => [
                ['login'],
                ['password']
            ],
        ];

        $this->load($data);

        $login = isset($this->attributes['login']) ? $this->attributes['login'] : null;
        $password = isset($this->attributes['password']) ? $this->attributes['password'] : null;

        if ($login && $password) {

            $user = $this->findOneWhere(['login' => $this->attributes['login']]);
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    foreach ($user as $key => $value) {
                        $_SESSION['user'][$key] = $value;
                    }
                    return true;
                }
            }
        } else {
            if (!$this->validate(['login' => $login, 'password' => $password])) {

                $this->getErrors();
            }
        }

        return false;
    }
}