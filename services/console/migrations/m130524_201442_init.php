<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'username' => $this->string(255)->unique(),
            'full_name' => $this->string(255),
            'phone' => $this->string(20),
            'note' => $this->string(255),
            'secret_key' => $this->string(32),
            'password' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex("idx_user", '{{%user}}', ["email", "phone"]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
