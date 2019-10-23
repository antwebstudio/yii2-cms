<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m160113_131522_create_cms_content_behavior extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_content_behavior}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'content_type_id' => $this->integer(11)->notNull(),
            'name' => $this->string(100)->notNull(),
            'class' => $this->string(255)->notNull(),
            'settings' => $this->text(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp',
            'apply_for_admin' => $this->boolean()->notNull()->defaultValue(1)
        ], $tableOptions);
		
		$this->createIndex(
			'cms_content_behavior_content_type_id_name_unq', 
			'{{%cms_content_behavior}}', 
			'content_type_id, name', 
			true);

		$this->addForeignKey(
			'cms_content_behavior_content_type_id_fk', 
			'{{%cms_content_behavior}}', 
			'content_type_id', 
			'{{%app_content_type}}', 
			'id');
	}

	public function down()
	{
		$this->dropForeignKey(
		'cms_content_behavior_content_type_id_fk', 
		'{{%cms_content_behavior}}');
		
		$this->dropTable('{{%cms_content_behavior}}');
	}

}