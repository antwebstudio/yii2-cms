<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165054_create_app_field extends Migration {

	public function safeUp() {

		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

		$this->createTable('{{%app_field}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'app_id' => 'tinyint(4) NOT NULL DEFAULT 1',
            'group_id' => $this->integer(11)->Null(),
            'name' => $this->string(255)->notNull(),
            'handle' => $this->string(58)->notNull(),
            'context' => $this->string(255)->notNull()->defaultValue('global'),
            'instructions' => $this->text(),
            'translatable' => $this->boolean()->unsigned()->notNull()->defaultValue(0),
            'type' => $this->string(150)->notNull(),
            'settings' => $this->text(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);
		
		$this->createIndex(
            'app_field_handle_context_unq_idx',
            '{{%app_field}}', 
            'app_id, 
            handle, 
            context', true);

        $this->createIndex(
            'app_field_context_idx', 
            '{{%app_field}}', 
            'context');

        $this->addForeignKey(
            'app_field_group_id_fk', 
            '{{%app_field}}', 
            'group_id', 
            '{{%app_field_group}}', 
            'id');
				
	}

	public function safeDown() {

		$this->dropForeignKey(
            'app_field_group_id_fk',
            '{{%app_field}}'
        );

		$this->dropTable('{{%app_field}}');
		
	}
	
	
}