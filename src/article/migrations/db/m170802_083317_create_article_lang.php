<?php

namespace ant\article\migrations\db;

use ant\components\Migration;
class m170802_083317_create_article_lang extends Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article_category_lang}}', [
            'id' => $this->primaryKey(),
			'master_id' => $this->integer(),
			'language' => $this->string(6)->notNull(),
            'slug' => $this->string(1024),
            'title' => $this->string(512),
			'subtitle' => $this->string(512),
            'body' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%article_lang}}', [
            'id' => $this->primaryKey(),
			'master_id' => $this->integer(),
			'language' => $this->string(6)->notNull(),
            'slug' => $this->string(1024),
            'title' => $this->string(512),
            'body' => $this->text(),
        ], $tableOptions);
		
		$this->createIndex('ix_article_lang_language', '{{%article_lang}}', 'language');
		$this->createIndex('ix_article_lang_master_id', '{{%article_lang}}', 'master_id');
		$this->createIndex('ix_article_category_lang_language', '{{%article_category_lang}}', 'language');
		$this->createIndex('ix_article_category_lang_master_id', '{{%article_category_lang}}', 'master_id');

        $this->addForeignKey('fk_article_lang_article', '{{%article_lang}}', 'master_id', '{{%article}}', 'id', 'cascade', 'cascade');
        $this->addForeignKey('fk_article_category_lang_article', '{{%article_category_lang}}', 'master_id', '{{%article_category}}', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        
        $this->dropForeignKey('fk_article_lang_article', '{{%article_lang}}');
        $this->dropForeignKey('fk_article_category_lang_article', '{{%article_category_lang}}');

        $this->dropTable('{{%article_lang}}');
        $this->dropTable('{{%article_category_lang}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170802_083317_create_article_lang cannot be reverted.\n";

        return false;
    }
    */
}
