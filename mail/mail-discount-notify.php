<?php
use yii\helpers\Html;
use yii\helpers\Url;
$imageFileName = dirname(__DIR__).'/web/images/'.$discount_data['img'];

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */


?>
<div>
    <div align="center" style="padding: 20px; border: 1px solid #1b66a2; color: red; margin: auto; float: left; max-width: 500px">
        <div style="text-align: center"><strong>Ваша карта</strong></div>
        <?=Html::img($message->embed($imageFileName), ['alt' => 'Карта лояльности', 'width' => '400'])?>
        <div style="text-align: center"><strong>Your card</strong></div>
    </div>
</div>
<div style="clear: both"></div>

<div style="text-align: center">
    <h2>Уважаемый(ая) <?=$user_name?>, поздравляем!</h2>

    <?php
    if(+$discount == 5):
        ?>
        <p>Вы стали нашим ценным покупателем!</p>
        <p>Ваша сумма покупок достигла  <?=$discount_data['sum']?>р, а это значит, что уровень Вашей персональной скидки стал <?=$discount?>%.</p>
        <p>Это наш <?=$discount_data['title']?> уровень, но самое интересное впереди!</p>
    <?php
    else:
        ?>
        <p>Вы наш самый ценный покупатель!</p>
        <p>Ваша сумма покупок достигла  <?=$discount_data['sum']?>р, а это значит, что уровень Вашей персональной скидки стал <?=$discount?>% и приобрёл статус <?=$discount_data['title']?></p>
    <?php
    endif;
    ?>
    <p>Примите наши поздравления! Ждём Вас снова в магазинах Lion of Porches.</p>


    <p></p>
    <p>-------------------------------- English ------------------------------------</p>
    <p></p>

    <h2>Congratulations, <?=$user_name?>!</h2>

    <p>You have accumulated a total purchase over <?=$discount_data['sum']?> rub, which means that your personal level of discount has become <?=$discount?>%
    </p>
    <p>This is our <?=$discount_data['title']?> level, but the most interesting is still ahead.</p>
    <p>Our sincere congratulations once again,  see you soon</p>

    <p>Cheers</p>

    <p>Lion Of Porches</p>
</div>

