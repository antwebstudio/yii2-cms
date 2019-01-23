<?php
use yii\helpers\Html;
use yii\helpers\Url;
?> 
<div class="articleItem selectedBar">
	<a href="<?= Url::to(['/article/article/view', 'id' => $model->id]) ?>">
		<h2 class="posttitle"><?= $model->title ?></h2>
		<span class="dates">12 / 06 / 2018</span>
		<p class="bar"></p>
		<?php /*
		Article Type : <?= $model->access_type ?>
		<?= strip_tags($model->body, '<br/>')?>
		*/ ?>
	</a>
</div>