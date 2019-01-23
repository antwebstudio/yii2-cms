<?php
namespace common\modules\cms\migrations\db;

use yii\db\Schema;
use yii\db\Migration;

class m151006_132742_create_cms_tag extends Migration {

    public function up() {

      $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 ENGINE=InnoDB';
        }

        $this->createTable('{{cms_tag}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'field_id' => $this->integer(11)->notNull(),
            'name' => $this->string(255)->notNull(),
            'counter' => $this->integer(11)->notNull(),
            'created_date' => $this->timestamp(),
            'last_updated' => 'timestamp NOT NULL DEFAULT current_timestamp ON UPDATE current_timestamp'
        ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{cms_tag}}');
    }

}
