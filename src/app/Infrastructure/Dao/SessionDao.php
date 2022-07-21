<?php

namespace App\Infrastructure\Dao;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * セッション情報を操作するDAO
 */
final class SessionDao
{
    /**
     * ログインユーザー情報のキー
     */
    private const AUTH = 'auth';

    /**
     * フォームに入力された情報のキー
     */
    private const FORM_INPUTS = 'formInputs';

    /**
     * 何かしらのメッセージ表示時のキー
     */
    private const MESSAGE = 'message';

    /**
     * エラー情報のキー
     */
    private const ERRORS = 'errors';

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->startSessionIfNeeded();
    }

    /**
     * まだセッションを開始していない場合はsession_start()を実行する
     *
     * @return void
     */
    private function startSessionIfNeeded(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    /**
     * ログインユーザー情報を取得する
     * 未ログイン時はnullを返す
     *
     * @return array|null
     */
    public function getAuth(): ?array
    {
        return $_SESSION[self::AUTH] ?? null;
    }

    /**
     * ログインユーザー情報を保存する
     *
     * @param integer $userId
     * @param string $name
     * @return void
     */
    public function setAuth(int $userId, string $name): void
    {
        $_SESSION[self::AUTH]['userId'] = $userId;
        $_SESSION[self::AUTH]['name'] = $name;
    }

    /**
     * フォームに入力された値を取得する
     * 主にエラー時に元々入力していた値を取得するために使用する
     *
     * @return array|null
     */
    public function getFormInputs(): ?array
    {
        return $_SESSION[self::FORM_INPUTS] ?? null;
    }

    /**
     * フォームに入力された値を取得し、元の情報は破棄する
     *
     * @return array|null
     */
    public function getFormInputsWithClear(): ?array
    {
        $formInputs = $this->getFormInputs();
        $this->clearFormInputs();
        return $formInputs;
    }

    /**
     * フォームに入力されていた値を保存する
     *
     * @param array $formInputs
     * @return void
     */
    public function setFormInputs(array $formInputs): void
    {
        $_SESSION[self::FORM_INPUTS] = $formInputs;
    }

    /**
     * フォームに入力されていた値を破棄する
     *
     * @return void
     */
    public function clearFormInputs(): void
    {
        unset($_SESSION[self::FORM_INPUTS]);
    }

    /**
     * メッセージを取得する
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $_SESSION[self::MESSAGE] ?? '';
    }

    /**
     * メッセージを取得し、その情報は破棄する
     *
     * @return string
     */
    public function getMessageWithClear(): string
    {
        $message = $this->getMessage();
        $this->clearMessage();
        return $message;
    }

    /**
     * メッセージを保存する
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $_SESSION[self::MESSAGE] = $message;
    }

    /**
     * メッセージを破棄する
     *
     * @return void
     */
    public function clearMessage(): void
    {
        unset($_SESSION[self::MESSAGE]);
    }

    /**
     * エラー情報を取得する
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $_SESSION[self::ERRORS] ?? [];
    }

    /**
     * エラー情報を取得し、その情報は破棄する
     *
     * @return array
     */
    public function getErrorsWithClear(): array
    {
        $errors = $this->getErrors();
        $this->clearErrors();
        return $errors;
    }

    /**
     * エラー情報を上書き保存する
     *
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $_SESSION[self::ERRORS] = $errors;
    }

    /**
     * 元々のエラー情報に追加する
     *
     * @return void
     */
    public function pushErrors(string $errorText): void
    {
        $_SESSION[self::ERRORS][] = $errorText;
    }

    /**
     * 保存されたエラー情報を破棄する
     *
     * @return void
     */
    public function clearErrors(): void
    {
        unset($_SESSION[self::ERRORS]);
    }

    /**
     * ログアウト時などに全てのセッション、クッキー情報を削除する
     *
     * @return void
     */
    public function destoryAll(): void
    {
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 4200, '/');
        }
        session_destroy();
    }
}
