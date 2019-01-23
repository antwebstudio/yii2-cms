<?php

namespace common\modules\cms\migrations\db;

use common\components\Migration;

class M170413032827_alter_timestamp_column extends Migration
{
    public function up()
    {
		$this->alterZeroDateTimeColumn('{{%cms_field}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout_tab}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_field_layout_field}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_relation}}', ['created_date']);
		$this->alterZeroDateTimeColumn('{{%cms_structure}}', ['created_date']);
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
