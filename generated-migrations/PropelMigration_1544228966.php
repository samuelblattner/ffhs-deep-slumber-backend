<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1544228966.
 * Generated on 2018-12-08 00:29:26 by samuelblattner
 */
class PropelMigration_1544228966
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "device" DROP CONSTRAINT "device_fk_22d367";

ALTER TABLE "device" ADD CONSTRAINT "device_fk_22d367"
    FOREIGN KEY ("user_id")
    REFERENCES "account" ("id")
    ON DELETE SET NULL;

COMMIT;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "device" DROP CONSTRAINT "device_fk_22d367";

ALTER TABLE "device" ADD CONSTRAINT "device_fk_22d367"
    FOREIGN KEY ("user_id")
    REFERENCES "account" ("id");

COMMIT;
',
);
    }

}