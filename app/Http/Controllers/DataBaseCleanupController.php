<?php

namespace App\Http\Controllers;

use app\libraries\database\Query;
use app\libraries\helpers\TimeTracker;
use App\Models\Revision;
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

    public function cleanRevisions()
    {
        $deleted = 0;
        $timer = new TimeTracker();
        $timer->startTimer('cleanup');

        Revision::orderBy('id', 'desc')->chunk(1000, function ($revisions) use (&$deleted) {
            /** @var Revision[] $revisions */
            foreach ($revisions as $revision) {
                $type = $revision->revisionable_type;

                if (!$type::whereId($revision->revisionable_id)->exists()) {
                    $revision->forceDelete();
                    $deleted++;
                }


            }
        });
        $timer->stopTimer('cleanup');
        $timer->getResults();
        echo "cleaned " . $deleted . " rows";
    }

    public function getMemoryUsage()
    {
        $usage = memory_get_usage();
        $steps = 0;
        while (($usage / 1024) >= 1) {
            $usage = $usage / 1024;
            $steps++;
        }
        $sizes = [
            "bytes",
            "KB",
            "MB",
            "GB",
            "TB",
            "PB",
            "XB"
        ];
        $endSize = $sizes[$steps];
        $usage = round($usage, 2);
        return $usage . " " . $endSize;
    }

}
