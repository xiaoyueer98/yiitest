<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pipeline_members`.
 */
class m170629_033408_create_pipeline_members_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('pipeline_members', [
            'id' => $this->primaryKey(),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('pipeline_members');
    }
}
