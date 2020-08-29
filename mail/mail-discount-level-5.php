<?php
use yii\helpers\Html;
use yii\helpers\Url;
$imageFileName = dirname(__DIR__).'/web/images/basic.jpg';
$user_name = 'test';

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

//debug($params);

?>
<div>
    <div align="center" style="padding: 20px; border: 1px solid #1b66a2; color: red; margin: auto; float: left">
        <span style="display: block; text-align: center"><strong>Ваша карта</strong></span>
            <?=Html::img($message->embed($imageFileName), ['alt' => 'Карта лояльности', 'width' => '400'])?>
        <span style="display: block; text-align: center"><strong>Your card</strong></span>
    </div>
</div>
<div style="clear: both"></div>

<div style="text-align: center">
    <h2>Уважаемый(ая) <?=$user_name?>, поздравляем!</h2>

    <p>Вы стали нашим ценным покупателем!</p>
    <p>Ваша сумма покупок достигла  10.000р, а это значит, что уровень Вашей персональной скидки стал 5%.</p>
    <p>Это наш BASIC уровень, но самое интересное впереди!</p>
    <p>Примите наши поздравления! Ждём Вас снова в магазинах Lion of Porches.</p>


    <p></p>
    <p>-------------------------------- English ------------------------------------</p>
    <p></p>

    <h2>Congratulations, <?=$user_name?>!</h2>

    <p>You have accumulated a total purchase over 10000 rub, which means that your personal level of discount has become 5%
    </p>
    <p>This is our Basic level, but the most interesting is still ahead.</p>
    <p>Our sincere congratulations once again,  see you soon</p>

    <p>Cheers</p>

    <p>Lion Of Porches</p>
</div>

