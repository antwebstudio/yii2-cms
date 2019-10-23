<?php

return [
    'id' => 'cms',
    'class' => \ant\cms\Module::className(),
    'isCoreModule' => false,
	'depends' => ['file', 'cms-core'],
];