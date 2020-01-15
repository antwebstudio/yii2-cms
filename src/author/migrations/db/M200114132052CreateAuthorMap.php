<?php

namespace ant\author\migrations\db;

use ant\db\Migration;

/**
 * Class M200114132052CreateAuthorMap
 */
class M200114132052CreateAuthorMap extends Migration
{
	protected $tableName = '{{%author_map}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'model_class_id' => $this->integer()->unsigned()->notNull(),
			'model_id' => $this->integer()->unsigned()->notNull(),
            'author_id' => $this->integer()->unsigned()->notNull(),
        ],  $this->getTableOptions());
		
		$this->addForeignKeyTo('{{%model_class}}', 'model_class_id');
		$this->addForeignKeyTo('{{%author}}', 'author_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKeyTo('{{%model_class}}', 'model_class_id');
        $this->dropForeignKeyTo('{{%author}}', 'author_id');
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200114132052CreateAuthorMap cannot be reverted.\n";

        return false;
    }
    */
}
