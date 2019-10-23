<?php

namespace ant\article\migrations\db;

use ant\db\Migration;
class m170803_074314_create_article_category_attachment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
		
        $this->createTable('{{%article_category_attachment}}', [
            'id' => $this->primaryKey(),
            'article_category_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
            'base_url' => $this->string(),
            'type' => $this->string(),
            'size' => $this->integer(),
            'name' => $this->string(),
            'created_at' => $this->integer()
        ], $tableOptions);
		
		$this->addForeignKey('fk_article_category_attachment_article', '{{%article_category_attachment}}', 'article_category_id', '{{%article_category}}', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_article_category_attachment_article', '{{%article_category_attachment}}');
        $this->dropTable('{{%article_category_attachment}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170803_074314_create_article_category_attachment cannot be reverted.\n";

        return false;
    }
    */
}
