<?php

return [
    'id' => 'cms',
    'class' => \common\modules\cms\Module::className(),
    'isCoreModule' => false,
	'depends' => ['file'],
];