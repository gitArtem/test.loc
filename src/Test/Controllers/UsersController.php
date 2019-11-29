<?php

namespace Test\Controllers;

use Test\Exceptions\InvalidArgumentException;
use Test\Exceptions\NotFoundException;
use Test\Models\Articles\Article;
use Test\Models\Users\User;
use Test\Models\Users\UserActivationService;
use Test\Services\EmailSender;
use Test\Services\UsersAuthService;

class UsersController extends AbstractController
{

    /**
     * User logout. Delete the cookie
     */
    public function logout()
    {
        UsersAuthService::deleteCookie();
        header('Location: /');
    }

    /**
     * User login. Save the cookie
     */
    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::saveCookie($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    /**
     * New User registration and send activation code
     */
    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }
            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);
                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);
                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }
        $this->view->renderHtml('users/signUp.php');
    }

    /**
     * Check activation code and confirm a new User
     * @param int $userId
     * @param string $activationCode
     */
    public function activate(int $userId, string $activationCode): void
    {
        $error = null;
        $user = User::getById($userId);
        if ($user === null) {
            $error = 'Пользователь не найден';
        } elseif ($user->isConfirmed()) {
            $error = 'Пользователь уже активирован';
        } elseif (!UserActivationService::checkActivationCode($user, $activationCode)) {
            $error = 'Неверный код активации';
        } else {
            $user->activate();
            UserActivationService::deleteActivationCode($user);
        }
        $this->view->renderHtml('users/activation.php', ['error' => $error]);
    }

    /**
     * Find and show all Users articles
     * @param string $userName
     * @throws NotFoundException
     */
    public function profile(string $userName)
    {
        $user = User::getByNickname($userName);
        if ($user === null) {
            throw new NotFoundException('Пользователь не найден');
        }
        $articles = Article::findAllByColumnValue('author_id', $user->getId());
        $this->view->renderHtml('users/viewArticles.php', ['articles' => $articles]);
    }

}