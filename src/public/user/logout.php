<?php

use App\Infrastructure\Dao\SessionDao;

require_once __DIR__ . '/../../app/Infrastructure/Redirect/redirect.php';
require_once __DIR__ . '/../../app/Infrastructure/Dao/SessionDao.php';

$sessionDao = new SessionDao();
$sessionDao->destoryAll();

redirect('./signin.php');
