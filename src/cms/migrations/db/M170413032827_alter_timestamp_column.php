<?php

namespace ant\cms\migrations\db;

use ant\db\Migration;

class M170413032827_alter_timestamp_column extends Migration
{
    public function up()
    {
		$this->alterZeroDateTimeColumn('{{%cms_field}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout_tab}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout_field}}', ['created_date']);
    }

    public function down()
    {
		
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
