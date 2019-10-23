<?php

namespace ant\comment\migrations\db;

use yii\db\Expression;
use ant\components\Migration;

class M180124072336_create_comment_group extends Migration
{
	protected $tableName = '{{%comment_group}}';
	
    public function safeUp()
    {
		$this->createTable($this->tableName, [
			'id' => $this->primaryKey(),
			'model_class' => $this->string()->notNull(),
			'model_id' => $this->integer()->null()->defaultValue(null),
			'foreign_pk' => $this->string()->null()->defaultValue(null),
		], $this->getTableOptions());
		
		$this->createIndex('comment_group_idx', $this->tableName, [
			new Expression('`model_class` ASC'),
			new Expression('`foreign_pk` ASC'),
		]);

    }

    public function safeDown()
    {
		$this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180124072336_create_comment_group cannot be reverted.\n";

        return false;
    }
    */
}
