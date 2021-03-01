<?php

use yii\db\Migration;

/**
 * Class m210301_074401_init_tables
 */
class m210301_074401_init_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'ref_source_id', $this->string(255)->null());

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        //table product
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(255)->unique(),
            'name' => $this->string(255),
            'type' => $this->string(50)->defaultValue('full'),
            'price' => $this->double(15.2)->defaultValue(0),
            'sale_profit' => $this->double(15.2)->defaultValue(0),
            'doctor_profit' => $this->double(15.2)->defaultValue(0),
            'active' => $this->tinyInteger()->defaultValue(0),
            'note' => $this->text()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //table product
        $this->createTable('{{%products_doctors}}', [
            'doctor_id' => $this->integer(),
            'product_id' => $this->integer(),
            'profit' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey(
            'pdoctor_fk_user',
            'products_doctors',
            'doctor_id',
            'user',
            'id',
            'CASCADE');
        $this->addForeignKey(
            'pdoctor_fk_product',
            'products_doctors',
            'product_id',
            'products',
            'id',
            'CASCADE');

        //source
        $this->createTable('{{%ref_sources}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(),
            'profit_percent' => $this->integer()->defaultValue(0),
            'max_percent' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //locations
        $this->createTable('{{%locations}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'active' => $this->tinyInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //media
        $this->createTable('{{%medias}}', [
            'id' => $this->primaryKey(),
            'file_path' => $this->text(),
            'type' => $this->string(255)->null(),
            'customer_id' => $this->integer()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //deposit
        $this->createTable('{{%deposits}}', [
            'id' => $this->primaryKey(),
            'billing_id' => $this->integer(),
            'total' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        //customer
        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'phone' => $this->string(25)->null(),
            'birthday' => $this->integer()->null(),
            'gender' => $this->string(50)->null(),
            'email' => $this->string(150)->null(),
            'address' => $this->string(255)->null(),
            'code' => $this->string(255)->null(),
            'created_code_time' => $this->integer()->null(),
            'ref_id' => $this->integer()->null(),
            'doctor_id' => $this->integer()->null(),
            'support_id' => $this->integer()->null(),
            'ref_source_id' => $this->integer()->null(),
            'location_id' => $this->integer()->null(),
            'district_code' => $this->string(255)->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        //customers_sales
        $this->createTable('{{%customers_sales}}', [
            'customer_id' => $this->integer(),
            'sale_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //billings
        $this->createTable('{{%billings}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'product_id' => $this->integer(),
            'quantity' => $this->integer()->defaultValue(0),
            'total' => $this->double()->defaultValue(),
            'discount' => $this->double()->defaultValue(0),
            'sub_total' => $this->double(15.2)->defaultValue(0),
            'status' => $this->tinyInteger()->defaultValue(0),
            'done_time' => $this->integer()->null(),
            'note' => $this->text()->null(),
            'location_id' => $this->integer()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        //billings
        $this->createTable('{{%billings_doctors}}', [
            'id' => $this->primaryKey(),
            'billing_id' => $this->integer(),
            'doctor_id' => $this->integer(),
            'percent' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //billings
        $this->createTable('{{%billings_profit}}', [
            'id' => $this->primaryKey(),
            'billing_id' => $this->integer(),
            'user_id' => $this->integer(),
            'profit' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        //billings
        $this->createTable('{{%billings_sales}}', [
            'id' => $this->primaryKey(),
            'billing_id' => $this->integer(),
            'sale_id' => $this->integer(),
            'percent' => $this->double(15.2)->defaultValue(0),
            'total_percent' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        //billings
        $this->createTable('{{%billings_supports}}', [
            'id' => $this->primaryKey(),
            'billing_id' => $this->integer(),
            'support_id' => $this->integer(),
            'percent' => $this->double(15.2)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210301_074401_init_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210301_074401_init_tables cannot be reverted.\n";

        return false;
    }
    */
}
