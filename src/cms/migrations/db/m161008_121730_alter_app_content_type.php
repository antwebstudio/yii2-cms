<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use ant\components\Migration;

class m161008_121730_alter_app_content_type extends Migration
{
	public function safeUp()
	{
		$this->alterZeroDateTimeColumn('{{%app_content_type}}', ['created_date']);
		
		$this->renameTable('{{%app_content_type}}', '{{%cms_entry_type}}');
	}

	public function safeDown()
	{
		$this->renameTable('{{%cms_entry_type}}','{{%app_content_type}}');
	}

	
}