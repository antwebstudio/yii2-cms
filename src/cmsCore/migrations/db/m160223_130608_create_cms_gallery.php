<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m160223_130608_create_cms_gallery extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

		$this->createTable('{{cms_gallery}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'content_id' => $this->integer(11)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

	}

	public function down()
	{
		$this->dropTable('{{cms_gallery}}');
	}

	
}
