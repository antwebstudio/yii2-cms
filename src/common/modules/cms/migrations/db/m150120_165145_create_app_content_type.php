<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165145_create_app_content_type extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_content_type}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'app_id' => 'tinyint(4) NOT NULL DEFAULT 1',
            'section_id' => $this->integer(11)->notNull()->defaultValue(0),
            'field_layout_id' => $this->integer(11)->null(),
            'name' => $this->string(255)->notNull(),
            'handle' => $this->string(255)->notNull(),
            'has_title_field' => $this->boolean()->unsigned()->notNull()->defaultValue(0),
            'title_label' => $this->string(255)->defaultValue('Title'),
            'title_format' => $this->string(255)->null(),
            'sequence' => 'tinyint(4) DEFAULT 0',
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp',
            'content_type' => $this->string(50)->notNull(),
            'permission_role_id' => $this->text(),
            'permission_default' => $this->boolean()->notNull()->defaultValue(0),
            'structure_id' => $this->integer(11),
            'is_single' => $this->boolean()->notNull()->defaultValue(0),
            'max_entry_per_user' => $this->smallInteger(5)->notNull()->defaultValue(0),
            'default_values' => $this->text(),
            'tostring_template' => $this->text()
        ], $tableOptions);
		
		$this->createIndex(
			'cms_entry_type_name_section_id_unq_idx',
		 	'{{%app_content_type}}', 
		 	'app_id, name, section_id', 
		 	true);

		$this->createIndex(
			'cms_entry_type_handle_section_id_unq_idx', 
			'{{%app_content_type}}', 
			'app_id, handle, section_id',
			 true);
	}

	public function down()
	{
		$this->dropTable('{{%app_content_type}}');
	}

}