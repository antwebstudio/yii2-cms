<?php

use common\modules\cms\models\Category;
use common\modules\cms\models\Entry;

return [
	'entry' => [
		'id' => 1, // matter
		'name' => 'fixture entry type 1',
		'content_type' => Entry::className(), // matter
		'handle' => 'fixtureEntryType1', // matter
	],
	'category' => [
		'id' => 2, // matter
		'name' => 'fixture entry type 2',
		'content_type' => Category::className(), // matter
		'handle' => 'fixtureEntryType2',
	],
	
];
