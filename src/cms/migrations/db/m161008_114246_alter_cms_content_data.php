<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use ant\db\Migration;

class m161008_114246_alter_cms_content_data extends Migration
{
	public function safeUp()
	{
		$this->alterZeroDateTimeColumn('{{%app_content}}', ['interviewTime', 'created_date']);
		
		$this->renameTable('{{%app_content}}', '{{%cms_content_data}}');
	}

	public function safeDown()
	{
		$this->renameTable('{{%cms_content_data}}', '{{%app_content}}');
	}

	
}