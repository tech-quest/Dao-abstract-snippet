<?php
require_once __DIR__ . '/../../app/Infrastructure/Dao/UserDao.php';
require_once __DIR__ . '/../../app/Infrastructure/Redirect/redirect.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Infrastructure\Dao\SessionDao;
use App\Infrastructure\Dao\UserDao;

$email = filter_input(INPUT_POST, 'email');
$name = filter_input(INPUT_POST, 'name');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword');
$sessionDao = new SessionDao();

if (empty($password) || empty($confirmPassword)) {
    $sessionDao->pushErrors('パスワードを入力してください');
}
if ($password !== $confirmPassword) {
    $sessionDao->pushErrors('パスワードが一致しません');
}
if (!empty($sessionDao->getErrors())) {
    $formInputs = [
        'name' => $name,
        'email' => $email,
    ];
    $sessionDao->setFormInputs($formInputs);
    redirect('./signup.php');
}

$userDao = new UserDao();
$user = $userDao->findByEmail($email);

if (!is_null($user)) {
    $sessionDao->pushErrors('すでに登録済みのメールアドレスです');
    redirect('./signup.php');
}

$userDao->create($name, $email, $password);
$sessionDao->setMessage('登録できました。');
redirect('./signin.php');
