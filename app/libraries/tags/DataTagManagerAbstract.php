<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2/4/2016
 * Time: 10:30 AM
 */

namespace app\libraries\tags;


use app\libraries\tags\collection\TagCollectionAbstract;
use app\libraries\types\TypeAbstract;

/**
 * Class DataTagManagerAbstract
 * @package app\libraries\tags
 */
abstract class DataTagManagerAbstract
{


    /**
     * Gets a datatag object by the row id. return null if none found.
     * @param int $id
     * @param bool $showTrashed
     * @return DataTag
     */
    public abstract function get_by_id($id, $showTrashed = false);

    /**
     * Gets a TagCollection of all the tags with the given parent id
     * @param int $id
     * @return TagCollectionAbstract
     */
    public abstract function get_by_parent_id($id);

    /**
     * Gets a Datatag by the name and parent id.
     * @param String $text
     * @param integer $parent_id Optional
     * @param bool $showTrashed
     * @return DataTag
     */
    public abstract function get_by_string( $text, $parent_id = null, $showTrashed = false);

    /**
     * Gets a Datatag by the name and89 parent id.
     * @param String $text
     * @return DataTag[]
     */
    public abstract function get_multiple_by_string( $text);

    /**
     * @param string $text
     * @param TypeAbstract $type
     * @param int $parent_id
     * @return DataTag|null
     */
    public abstract function get_by_string_and_type($text, $type, $parent_id = null);

    /**
     * Removes unworthy characters from the tag Identifier
     * @param string $name The string to validate
     * @return string
     */
    public function validate_name($name)
    {
        $name = preg_replace('!\s+!', '_', $name);
        // $name = str_replace(" ", "_", $name);
        $name = str_replace("'", "", $name);
        $name = str_replace("\\", "", $name);
        $name = str_replace("/", "", $name);
        $name = str_replace("/", "", $name);
        $name = str_replace("(", "", $name);
        $name = str_replace(")", "", $name);
        return $name;
    }

    /**
     * Use this function only if you do not have the datatag object. This is slower than calling findChildBySortNumber
     * @param integer $sort_number
     * @param TypeAbstract $type
     * @param integer $parent_id
     * @return DataTag Will also return null if nothing found
     */
    public abstract function get_by_sort_id( $sort_number,  $type,  $parent_id);

    /**
     * @param String|integer $nameorID
     * @param integer|null $parentID
     * @return bool Exists
     */
    public abstract function exists($nameorID, $parentID = null);


}