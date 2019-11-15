<?php

namespace App\Controllers;

use App\Models\Users;
use Igoframework\Core\Base\View;

class UserController extends BaseController
{
    public function registerAction()
    {
        if (!empty($_POST)) {
            $user = new Users();
            $data = $_POST;
            $user->load($data);
            
            if (!$user->validate($data) || !$user->checkUnique()) {
                $user->getErrors();
                redirect();
            }

            $user->attributes['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if ($user->save($user->attributes)) {
                $_SESSION['success'] = 'Вы успешно зарегистрированы!';
            } else {
                $_SESSION['errors'] = 'Ошибка регистрации!';
            }
            redirect();
        }
        View::setMeta('Регистрация');
    }

    public function loginAction()
    {
        if (! empty($_POST)) {
            $user = new Users();
            $data = $_POST;
            if ($user->login($data)) {
                $_SESSION['success'] = 'Авторизация прошла успешно';
            } else {
                if (! isset($_SESSION['errors'])) {
                    $_SESSION['errors'] = 'Неверный логин/пароль';
                }
            }
            redirect();
        }

        View::setMeta('Авторизация');
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        redirect();
    }
}