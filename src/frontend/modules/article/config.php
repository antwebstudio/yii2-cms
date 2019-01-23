<?php

return [
    'id' => 'article',
    'class' => \frontend\modules\article\Module::className(),
    'isCoreModule' => false,
	'depends' => [], // Payment module should not depends on any other module
];
?>