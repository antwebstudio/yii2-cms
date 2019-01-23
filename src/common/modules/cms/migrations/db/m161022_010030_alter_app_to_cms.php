<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m161022_010030_alter_app_to_cms extends Migration
{
	public function safeUp()
	{
		$this->renameTable('{{%app_field_group}}','{{%cms_field_group}}');

		$this->renameTable('{{%app_field}}','{{%cms_field}}');

		$this->renameTable('{{%app_field_layout}}','{{%cms_field_layout}}');

		$this->renameTable('{{%app_field_layout_tab}}','{{%cms_field_layout_tab}}');

		$this->renameTable('{{%app_field_layout_field}}','{{%cms_field_layout_field}}');

		$this->renameTable('{{%app_relation}}','{{%cms_relation}}');

		$this->renameTable('{{%app_structure}}','{{%cms_structure}}');

		$this->renameTable('{{%app_structure_content}}','{{%cms_structure_content}}');

		$this->renameTable('{{%app_content_lang}}','{{%cms_content_lang}}');



        
	}

	public function safeDown()
	{
		$this->renameTable('{{%cms_field_group}}','{{%app_field_group}}');

		$this->renameTable('{{%cms_field}}','{{%app_field}}');

		$this->renameTable('{{%cms_field_layout}}','{{%app_field_layout}}');

		$this->renameTable('{{%cms_field_layout_tab}}','{{%app_field_layout_tab}}');

		$this->renameTable('{{%cms_field_layout_field}}','{{%app_field_layout_field}}');

		$this->renameTable('{{%cms_relation}}','{{%app_relation}}');

		$this->renameTable('{{%cms_structure}}','{{%app_structure}}');

		$this->renameTable('{{%cms_structure_content}}','{{%app_structure_content}}');

		$this->renameTable('{{%cms_content_lang}}','{{%app_content_lang}}');
	}

	
}