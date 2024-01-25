<?php

namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;

class GeneratePasswordController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionIndex(string $password = '123456'): int
    {
        echo Yii::$app->getSecurity()->generatePasswordHash($password) . "\n";

        return ExitCode::OK;
    }
}
