<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m161224_211133_create_behaviors extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%behaviors}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'behavior_name' => $this->string(255)->notNull(),
        ], $tableOptions);

        /*$this->createIndex(
            'behaviors_id_idx', 
            'behaviors', 
            'id');*/

       $this->insert('{{%behaviors}}', [
            'id' => 1,
            'behavior_name' => 'SendEmailBehavior',
        ]);

       $this->insert('{{%behaviors}}', [
            'id' => 2,
            'behavior_name' => 'EmailBehavior',
        ]);
	}




	public function down()
	{
		$this->dropTable('{{%behaviors}}');
	}

}