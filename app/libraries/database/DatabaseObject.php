<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 9/17/2015
 * Time: 2:43 PM
 */

namespace app\libraries\database;

/**
 * A Class that contains basic properties of a database object
 * Class DatabaseObject
 * @package app\libraries\database
 * @abstract
 * @access  public
 *
 */
abstract class DatabaseObject
{
    
    /**
     * The Database row ID
     * @var int
     * @access protected
     */
    protected $id = null;
    /**
     * The updated at column in a database row
     * var String
     * @access protected
     */
    protected $updated_at = null;
    /**
     * The created at column in a database row
     * @var string
     * @access protected
     */
    protected $created_at = null;
    
    /**
     * Gets the row ID of the current Database Object
     * @return int
     */
    public function get_id()
    {
        return $this->id;
    }
    
    /**
     * Sets the row ID of the Database Object
     *
     * @param int $id
     */
    public function set_id(int $id)
    {
        $this->id = $id;
    }
    
    /**
     * Gets the date at when the object was updated.
     * @return string
     */
    public abstract function updated_at();
    
    /**
     * Gets the date at when the object was created
     * @return string
     */
    public abstract function created_at();
    
    /**
     * Deletes The Object
     * @return mixed
     */
    public abstract function delete();
    
    /**
     * Returns the ID as a string for a unique Identifier
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
    
    /**
     * Returns a standard object encoding of this Type
     * @return \stdClass
     */
    public abstract function toStdClass();
}