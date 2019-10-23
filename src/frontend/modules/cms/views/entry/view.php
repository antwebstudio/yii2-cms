<?php 
use yii\helpers\Html;
use ant\cms\models\Entry;

$entry->processValue();
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1><?= $entry->name ?></h1>

			<?php if (isset($entry->featuredImage)): ?>
				<?= Html::img($entry->featuredImage) ?>
			<?php endif ?>

			<?php if (isset($entry->articleContent)): ?>
				<?= $entry->articleContent ?>
			<?php endif ?>

			<?php if (isset($entry->link)): ?>
				<?= $entry->link ?>
			<?php endif ?>
		</div>
	</div>
</div>