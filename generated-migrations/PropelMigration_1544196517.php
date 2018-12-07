<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1544196517.
 * Generated on 2018-12-07 15:28:37 by samuelblattner
 */
class PropelMigration_1544196517
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

ALTER TABLE "alarm"

  ALTER COLUMN "earliest" TYPE TIME USING NULL,

  ALTER COLUMN "latest" TYPE TIME USING NULL;

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

ALTER TABLE "alarm"

  ALTER COLUMN "earliest" TYPE TIMESTAMP USING NULL,

  ALTER COLUMN "latest" TYPE TIMESTAMP USING NULL;

COMMIT;
',
);
    }

}