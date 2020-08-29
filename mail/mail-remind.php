<?php
use yii\helpers\Html;
?>

<h3>Уважаемый(ая) <?=$user_name?>!</h3>

<?=Html::img($message->embed($image))?>
<br>
Воспользоваться скидкой Вы можете до <?=$date_stop?>


