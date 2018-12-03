<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1543840015.
 * Generated on 2018-12-03 12:26:55 by samuelblattner
 */
class PropelMigration_1543840015
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

ALTER TABLE "permission"

  ALTER COLUMN "key" TYPE VARCHAR(64),

  ALTER COLUMN "key" DROP DEFAULT;

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

CREATE SEQUENCE permission_key_seq;

ALTER TABLE "permission"

  ALTER COLUMN "key" TYPE INTEGER
   USING CASE WHEN trim(key) SIMILAR TO \'[0-9]+\'
        THEN CAST(trim(key) AS integer)
        ELSE NULL END,

  ALTER COLUMN "key" SET DEFAULT nextval(\'permission_key_seq\'::regclass);

COMMIT;
',
);
    }

}