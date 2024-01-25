<?php

/**
 * @var User         $model
 * @var yii\web\View $this
 */

use app\models\User;
use yii\helpers\Html;

$this->title = 'Профиль';

$script = <<<SCRIPT
    $.ajax({
        url: 'https://randomuser.me/api/?gender=male&nat=ua',
        dataType: 'json',
        success: function(data) {
            let random = data.results[0];
            $('.content__avatar').css('background', `#8f6ed5 url('\${random.picture.large}') center center no-repeat`);
            $('.content__location').html(`\${random.location.city}, \${random.location.country}`);
        }
    });
SCRIPT;
$this->registerJs($script, \yii\web\View::POS_END);

$this->registerCssFile(Yii::getAlias('@web').'/css/profile.css');
$this->registerJsFile(Yii::getAlias('@web').'/js/profile.js', ['position' => \yii\web\View::POS_END]);
?>


<div class="profile-page">
    <div class="content">
        <div class="content__cover" style="margin-bottom: 80px;">
            <div class="content__avatar" style="background: #157be7 center center no-repeat"></div>
            <div class="content__bull">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>
        <div class="content__title">
            <h1>Добрый день, <?= $model->username ?></h1>
        </div>
        <div class="content__button">
            <a class="button" href="#">
                <?=
                Html::beginForm(['/logout']) .
                Html::submitButton('Выход', ['class' => 'btn logout']) .
                Html::endForm()
                ?>
            </a>
        </div>
    </div>

    <div class="bg">
        <div><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>
    <div class="theme-switcher-wrapper" id="theme-switcher-wrapper"><span>Themes color</span>
        <ul>
            <li><em class="is-active" data-theme="orange"></em></li>
            <li><em data-theme="green"></em></li>
            <li><em data-theme="purple"></em></li>
            <li><em data-theme="blue"></em></li>
        </ul>
    </div>
</div>
