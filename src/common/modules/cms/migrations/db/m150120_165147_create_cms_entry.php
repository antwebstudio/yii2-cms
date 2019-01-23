<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m150120_165147_create_cms_entry extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%cms_entry}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'section_id' => $this->integer(11)->notNull()->defaultValue(0),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp',
            'content_id' => $this->integer(11)->notNull(),
            'published_date' => $this->timestamp(),
            'expire_date' => $this->timestamp()->Null()
        ], $tableOptions);
		

		$this->addForeignKey(
			'cms_entry_content_id_fk', 
			'{{%cms_entry}}', 
			'content_id', 
			'{{%app_content}}', 
			'id', 
			'CASCADE', 'RESTRICT');
	}

	public function down()
	{
		$this->dropForeignKey(
			'cms_entry_content_id_fk',
			'{{%cms_entry}}');

		$this->dropTable('{{%cms_entry}}');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}