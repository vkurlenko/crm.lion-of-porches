<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

//debug($params);

?>
    <h2>Уважаемый(ая) <?=$user_name?>!</h2>

    <!--<h3>Ваш QR-код</h3>-->

    <p>Благодарим Вас за участие в нашей программе лояльности!</p>

    <p>Если Вы еще не являетесь пользователем приложения Pinbonus, то вам необходимо сделать три шага:</p>

    <ol>
        <li>Загрузить приложение из <?=Html::a('GooglePlay', 'https://play.google.com/store/apps/details?id=com.pinbonus2');?> или <?=Html::a('AppStore', 'https://itunes.apple.com/ru/app/diskontnye-i-bonusnye-karty/id904237311');?>;</li>
        <li>Найти Lion of Porches в приложении Pinbonus;</li>
        <li>Отсканировать Ваш уникальный QR-код в поле <em>Штрихкод</em>:
            <p><?=Html::img($message->embed($imageFileName), ['alt' => 'Ваш уникальный QR-код'])?></p>
            и сохранить карту.
            <br>
        </li>
    </ol>

<p><em>Предъявите электронную карту продавцу в магазине чтобы воспользоваться Вашей персональной скидкой.</em></p>

<p></p>
<p>-------------------------------- English ------------------------------------</p>
<p></p>

<h2>Dear <?=$user_name?>!</h2>

<!--<h3>Ваш QR-код</h3>-->

<p>We would like to thank you for participating in our loyalty program!</p>
In order to get your personal card, you should download the Pinbonus application.

<p>Please follow the following steps:</p>

<ol>
    <li>Find and download "Pinbonus' from  <?=Html::a('GooglePlay', 'https://play.google.com/store/apps/details?id=com.pinbonus2');?> or <?=Html::a('AppStore', 'https://itunes.apple.com/ru/app/diskontnye-i-bonusnye-karty/id904237311');?>;</li>
    <li>Find "Lion Of Porches" in the application and tap on it;</li>
    <li>Scan the attached QR code using the scanner:
        <p><?=Html::img($message->embed($imageFileName), ['alt' => 'Ваш уникальный QR-код'])?></p>
        <br>
    </li>
</ol>

<p><em>After saving the card, you will be able to show it and enjoy the preveliges in all Lion Of Porches stores in the Russian Federation.</em></p>

<p>Best regards</p>
<p>Lion Of Porches</p>



<!--https://play.google.com/store/apps/details?id=com.pinbonus2-->
<!--Если Вы пользователь IOS то Вам нужно просто перейти по этой ссылке.-->

    <!--<h3>Ссылка на электронную карту LOP</h3>

--><?/*=Html::a('http://lion-of-porches.ru/passes/sample.pkpass', 'http://lion-of-porches.ru/passes/sample.pkpass')*/?>
