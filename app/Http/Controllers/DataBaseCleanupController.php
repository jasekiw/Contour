<?php

namespace App\Http\Controllers;

use app\libraries\database\Query;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DataBaseCleanupController extends Controller
{
    public function cleanDeletedTags()
    {
        $nl = "<br />";
        $query =
            "DELETE FROM tags WHERE deleted_at IS NOT NULL ";
        $affected = Query::getPDO()->exec($query);
        echo $affected . " tags were deleted $nl";

        $query =
            "DELETE FROM data_blocks WHERE deleted_at IS NOT NULL";
        $affected = Query::getPDO()->exec($query);
        echo $affected . " datablocks were deleted $nl";

        $query = "
            SELECT
              ref.data_block_id AS data_block_id,
              ref.tag_id AS tag_id
            FROM tags_reference ref
              LEFT JOIN data_blocks ON ref.data_block_id = data_blocks.id
              LEFT JOIN tags ON ref.tag_id = tags.id
            WHERE data_blocks.id IS NULL OR ref.data_block_id IS NULL OR
              tags.id IS NULL";
        $brokenIds = Query::getPDO()->query($query);
        $affected = sizeof($brokenIds);
        foreach ($brokenIds as $row) {
            $query = "DELETE" . " FROM tags_reference WHERE ";
            if (isset($row['data_block_id']))
                $query .= "data_block_id = " . $row['data_block_id'] . " AND ";
            if (isset($row['tag_id']))
                $query .= "tag_id = " . $row['tag_id'] . "";

            Query::getPDO()->exec($query);

        }
    }

    public function fixBroken()
    {
        $query = "
            SELECT
              ref.data_block_id AS data_block_id,
              ref.tag_id AS tag_id
            FROM tags_reference ref
              LEFT JOIN data_blocks ON ref.data_block_id = data_blocks.id
              LEFT JOIN tags ON ref.tag_id = tags.id
            WHERE data_blocks.id IS NULL OR ref.data_block_id IS NULL OR
              tags.id IS NULL";
        $brokenIds = Query::getPDO()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        $affected = sizeof($brokenIds);
        foreach ($brokenIds as $row) {
            $query = "DELETE" . " FROM tags_reference WHERE ";
            if (isset($row['data_block_id']))
                $query .= "data_block_id = " . $row['data_block_id'] . " AND ";
            if (isset($row['tag_id']))
                $query .= "tag_id = " . $row['tag_id'] . "";

            Query::getPDO()->exec($query);
        }
        echo "deleted " . $affected . " broken references";
    }


}
