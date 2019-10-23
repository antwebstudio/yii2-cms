<?php
namespace ant\cmsCore\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150331_041613_create_app_structure extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_structure}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

	}

	public function down()
	{
		$this->dropTable('{{%app_structure}}');
	}

}