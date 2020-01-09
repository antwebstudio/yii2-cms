<?php

namespace ant\comment\migrations\db;

use yii\db\Migration;

/**
 * Class M200107235853AlterComment
 */
class M200107235853AlterComment extends Migration
{
	protected $tableName = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'author_name', $this->string()->defaultValue(Null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'author_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200107235853AlterComment cannot be reverted.\n";

        return false;
    }
    */
}
