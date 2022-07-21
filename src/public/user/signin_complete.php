<?php

use App\Infrastructure\Dao\UserDao;
use App\Infrastructure\Dao\SessionDao;

require_once __DIR__ . '/../../app/Infrastructure/Dao/UserDao.php';
require_once __DIR__ . '/../../app/Infrastructure/Dao/SessionDao.php';
require_once __DIR__ . '/../../app/Infrastructure/Redirect/redirect.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
$sessionDao = new SessionDao();

if (empty($email) || empty($password)) {
    $sessionDao->pushErrors('パスワードとメールアドレスを入力してください');
    redirect('./signin.php');
}

$userDao = new UserDao();
$user = $userDao->findByEmail($email);

if (!password_verify($password, $user['password'])) {
    $sessionDao->pushErrors('メールアドレスまたは<br />パスワードが違います');
    redirect('./signin.php');
}

$sessionDao->setAuth($user['id'], $user['name']);
redirect('../index.php');
