<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1544175501.
 * Generated on 2018-12-07 09:38:21 by samuelblattner
 */
class PropelMigration_1544175501
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

ALTER TABLE "permission_citizen" DROP CONSTRAINT "permission_citizen_fk_2b894c";

ALTER TABLE "permission_citizen" DROP CONSTRAINT "permission_citizen_fk_e02c8c";

ALTER TABLE "permission_citizen" ADD CONSTRAINT "permission_citizen_fk_2b894c"
    FOREIGN KEY ("permission_id")
    REFERENCES "permission" ("id")
    ON DELETE CASCADE;

ALTER TABLE "permission_citizen" ADD CONSTRAINT "permission_citizen_fk_e02c8c"
    FOREIGN KEY ("citizen_id")
    REFERENCES "abstract_citizen" ("id")
    ON DELETE CASCADE;

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

ALTER TABLE "permission_citizen" DROP CONSTRAINT "permission_citizen_fk_2b894c";

ALTER TABLE "permission_citizen" DROP CONSTRAINT "permission_citizen_fk_e02c8c";

ALTER TABLE "permission_citizen" ADD CONSTRAINT "permission_citizen_fk_2b894c"
    FOREIGN KEY ("permission_id")
    REFERENCES "permission" ("id");

ALTER TABLE "permission_citizen" ADD CONSTRAINT "permission_citizen_fk_e02c8c"
    FOREIGN KEY ("citizen_id")
    REFERENCES "abstract_citizen" ("id");

COMMIT;
',
);
    }

}