<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/profile');
        }

        $loginAttempts = $unblockIn = -1;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                $this->resetFlash();
                return $this->redirect('/profile');
            }

            $loginAttempts = Yii::$app->session->get('login_attempts') ?? 0;
            if ($loginAttempts >= 3) {
                $blockedUntil = Yii::$app->session->get('blocked_until');
                if (!$blockedUntil) {
                    $blockedUntil = time() + 300;
                    Yii::$app->session->set('blocked_until', $blockedUntil);
                }

                $unblockIn = $blockedUntil - time();
                Yii::$app->session->setFlash('max_login_attempts_reached', $unblockIn);
            }
            Yii::$app->session->set('login_attempts', ++$loginAttempts);
        }

        if ($unblockIn < 1 && $loginAttempts > 3) {
            $this->resetFlash();
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return $this->redirect('/login');
    }

    private function resetFlash(): void
    {
        Yii::$app->session->remove('login_attempts');
        Yii::$app->session->remove('blocked_until');
        Yii::$app->session->removeFlash('max_login_attempts_reached');
    }
}
