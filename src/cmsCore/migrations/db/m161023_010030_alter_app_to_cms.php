<?php
namespace ant\cmsCore\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m161023_010030_alter_app_to_cms extends Migration
{
	public function safeUp()
	{
		$this->renameTable('{{%app_relation}}','{{%cms_relation}}');
		$this->renameTable('{{%app_structure}}','{{%cms_structure}}');
		$this->renameTable('{{%app_structure_content}}','{{%cms_structure_content}}');
	}

	public function safeDown()
	{

		$this->renameTable('{{%cms_relation}}','{{%app_relation}}');
		$this->renameTable('{{%cms_structure}}','{{%app_structure}}');
		$this->renameTable('{{%cms_structure_content}}','{{%app_structure_content}}');
	}

	
}