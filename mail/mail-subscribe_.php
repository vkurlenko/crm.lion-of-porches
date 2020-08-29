<?php
use yii\helpers\Html;
?>

<h1><?=$subject?></h1>

<?=$body?>

<?php
if(is_file($image)):
?>
    <?=Html::img($message->embed($image))?>
<?php
endif;
?>


