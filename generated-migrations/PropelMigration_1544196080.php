<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1544196080.
 * Generated on 2018-12-07 15:21:20 by samuelblattner
 */
class PropelMigration_1544196080
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

CREATE TABLE "alarm"
(
    "id" serial NOT NULL,
    "earliest" TIMESTAMP,
    "latest" TIMESTAMP,
    "active" BOOLEAN,
    "device_hwid" VARCHAR(32),
    PRIMARY KEY ("id")
);

ALTER TABLE "alarm" ADD CONSTRAINT "alarm_fk_be680f"
    FOREIGN KEY ("device_hwid")
    REFERENCES "device" ("hwid");

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

DROP TABLE IF EXISTS "alarm" CASCADE;

COMMIT;
',
);
    }

}