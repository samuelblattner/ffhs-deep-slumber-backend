<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1544185603.
 * Generated on 2018-12-07 12:26:43 by samuelblattner
 */
class PropelMigration_1544185603
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

CREATE TABLE "sleep_cycle"
(
    "id" serial NOT NULL,
    "start" TIMESTAMP,
    "end" TIMESTAMP,
    "duration" INTEGER,
    "device_hwid" VARCHAR(32),
    PRIMARY KEY ("id")
);

CREATE TABLE "sleep_event"
(
    "id" serial NOT NULL,
    "timestamp" TIMESTAMP,
    "type" VARCHAR(16),
    "sleep_cycle_id" INTEGER,
    PRIMARY KEY ("id")
);

ALTER TABLE "sleep_cycle" ADD CONSTRAINT "sleep_cycle_fk_be680f"
    FOREIGN KEY ("device_hwid")
    REFERENCES "device" ("hwid");

ALTER TABLE "sleep_event" ADD CONSTRAINT "sleep_event_fk_babf2d"
    FOREIGN KEY ("sleep_cycle_id")
    REFERENCES "sleep_cycle" ("id");

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

DROP TABLE IF EXISTS "sleep_cycle" CASCADE;

DROP TABLE IF EXISTS "sleep_event" CASCADE;

COMMIT;
',
);
    }

}