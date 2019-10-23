<?php
namespace ant\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m160423_132833_create_app_content_lang extends Migration
{
	public function up()
	{
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{%app_content_lang}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'app_content_id' => $this->integer(11)->notNull(),
            'lang_id' => $this->string(6)->notNull(),
            'data' => $this->text(),
            'name' => $this->string(255),
            'slug' => $this->string(255),
            'last_updated_by' => $this->integer(11)->notNull()->defaultValue(0),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);

		$this->createIndex(
            'app_content_lang_app_content_id_lang_id_idx', 
            '{{%app_content_lang}}', 
            'app_content_id, 
            lang_id'
		);

		$this->addForeignKey(
			'app_content_lang_app_content_id_fk', 
			'{{%app_content_lang}}', 
			'app_content_id', 
			'{{%app_content}}', 
			'id', 
			'CASCADE', 'RESTRICT'
		);
	}

	public function down()
	{
        $this->dropForeignKey(
            'app_content_lang_app_content_id_fk', 
            '{{%app_content_lang}}'
		);

		$this->dropTable('{{%app_content_lang}}');
	}

}