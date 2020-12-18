<?php

namespace ant\cms\migrations\db;

use yii\db\Migration;

/**
 * Class M201218074029AlterCmsEntry
 */
class M201218074029AlterCmsEntry extends Migration
{
    protected $tableName = '{{%cms_entry}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', $this->boolean()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201218074029AlterCmsEntry cannot be reverted.\n";

        return false;
    }
    */
}
