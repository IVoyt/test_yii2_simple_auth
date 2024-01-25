<?php

/**
 * @var yii\web\View $this
 * @var ActiveForm   $form
 * @var LoginForm    $model
 */

use app\models\forms\LoginForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизация';

$secondsLeft = Yii::$app->session->getFlash('max_login_attempts_reached') ?? 0;

if ($secondsLeft) {
    $script = <<<SCRIPT
        let secondsLeft = $secondsLeft;
        let defaultSecondsText = 'секунд';
        
        function estimate() {
            secondsLeft -= 1;
            if (secondsLeft < 1) {
                clearTimeout(timer);
                window.location = window.location.href;
            }
                
            let secondsText = defaultSecondsText;
            let lastDigit = Number.isInteger(secondsLeft)
                ? secondsLeft % 10
                : secondsLeft.toString().slice(-1);
            let lastTwoDigits = parseInt(secondsLeft.toString().slice(-2));
            if (lastTwoDigits < 11 || lastTwoDigits > 14) {
                if (lastDigit === 1 && secondsLeft !== 11) {
                    secondsText += 'у';
                } else if (lastDigit > 0 && lastDigit < 5) {
                    secondsText += 'ы';
                }
            }
            $('#seconds-left').html(secondsLeft);
            $('#seconds-text').html(secondsText);
            
            setTimeout(function() {
                estimate()
            }, 1000);
        }
        
        let timer = setTimeout(function() { estimate() }, 1);
    SCRIPT;

    $this->registerJs($script);
}


?>
<style>
    html,
    body {
        height: 100%;
    }

    .form-signin {
        max-width: 450px;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<div class="container-fluid">
    <div class="row text-center">
        <div class="offset-3 col-6">
            <div class="form-signin m-auto">
                <?php if (Yii::$app->session->hasFlash('max_login_attempts_reached')): ?>
                    <div class="alert alert-danger">
                        <h4>Вы ввели неправильные данные!</h4>
                        <span>
                            Попробуйте ещё раз через
                            <span id="seconds-left"></span>
                            <span id="seconds-text"></span>
                        </span>
                    </div>
                <?php else:
                    $form = ActiveForm::begin(
                        [
                            'id'          => 'auth-form',
                            'fieldConfig' => [
                                'template'     => "{input}\n{label}\n{error}",
                                'labelOptions' => ['class' => 'mr-lg-3'],
                                // 'inputOptions' => ['class' => 'form-control'],
                                // 'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                            ],
                        ]
                    );

                    echo $form->field($model, 'email', ['options' => ['class' => 'form-floating']])
                        ->label('E-mail')
                        ->input(
                            'email',
                            ['autofocus' => true, 'placeholder' => 'E-mail']
                        );

                    echo $form->field($model, 'password', ['options' => ['class' => 'form-floating']])
                        ->label('Пароль')
                        ->passwordInput(
                            ['placeholder' => 'Пароль']
                        );
                    ?>

                    <div class="form-group" style="margin-top: 15px;">
                        <?php echo Html::submitButton(
                            'Авторизоваться',
                            ['class' => 'w-100 btn btn-lg btn-primary', 'name' => 'auth-button']
                        ) ?>
                    </div>

                    <?php  ActiveForm::end();
                endif; ?>
            </div>
        </div>
    </div>
</div>
