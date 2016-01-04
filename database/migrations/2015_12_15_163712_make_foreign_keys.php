<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Types forieng key to type cateogries
         */
//        DB::statement('
//                        ALTER TABLE laravel.types
//                        ADD CONSTRAINT types_type_categories_id_fk
//                        FOREIGN KEY (type_category_id) REFERENCES type_categories (id);
//		');
//
//        /**
//         * Tags to types foreign key
//         */
//        DB::statement('
//                        ALTER TABLE laravel.tags
//						ADD CONSTRAINT tags_types_id_fk
//						FOREIGN KEY (type_id) REFERENCES types (id);'
//        );
//
//        /**
//         * Datablocks foreign keys
//         */
//        DB::statement('
//                        ALTER TABLE laravel.data_blocks
//						ADD CONSTRAINT data_blocks_types_id_fk
//						FOREIGN KEY (type_id) REFERENCES types (id);'
//        );
//
//        /**
//         * Tags reference indices
//         */
////        DB::statement('CREATE INDEX data_block_id ON tags_reference (data_block_id);');
////        DB::statement('CREATE INDEX tag_id ON tags_reference (tag_id);');
//
//        /**
//         * User meta foreign keu
//         */
//        DB::statement('
//                        ALTER TABLE laravel.user_meta
//						ADD CONSTRAINT user_meta_user_id_fk
//						FOREIGN KEY (user_id) REFERENCES users (id);'
//        );
//
//        /**
//         * User permission foreign key
//         */
//        DB::statement('
//                        ALTER TABLE laravel.users
//						ADD CONSTRAINT users_user_access_group_id_fk
//						FOREIGN KEY (user_access_group_id) REFERENCES user_access_groups (id);'
//        );
//
//        DB::statement('
//                        ALTER TABLE laravel.permissions_reference
//						ADD CONSTRAINT permissions_reference_permissions_group_id_fk
//						FOREIGN KEY (permission_group_id) REFERENCES user_access_groups (id);'
//        );
//
//        DB::statement('
//                        ALTER TABLE laravel.permissions_reference
//						ADD CONSTRAINT permissions_reference_permissions_id_fk
//						FOREIGN KEY (permission_id) REFERENCES permissions (id);'
//        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement('ALTER TABLE laravel.users DROP FOREIGN KEY users_user_access_group_id_fk;');
//        DB::statement('ALTER TABLE laravel.permissions_reference DROP FOREIGN KEY permissions_reference_permissions_id_fk;');
    }
}
