<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165001_create_app_field_group extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }


        $this->createTable('{{%app_field_group}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'app_id' => 'tinyint(4) NOT NULL DEFAULT 1',
            'name' => $this->string(255)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' =>  'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
           
        ], $tableOptions);

        $this->createIndex(
            'app_field_group_name_unq_idx', 
            '{{%app_field_group}}', 
            'app_id, name', true);
	}

	public function safeDown()
	{
		$this->dropTable('{{%app_field_group}}');
	}

	
}