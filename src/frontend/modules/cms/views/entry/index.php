<?php 
use common\modules\cms\models\Entry;

$entries = Entry::find()->type($type)->all();
?>

<?php foreach ($entries as $entry): ?>
	<h2><a href="<?= $entry->url ?>"><?= $entry->name ?></a></h2>
<?php endforeach; ?>