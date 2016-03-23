<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 1/13/2016
 * Time: 12:05 AM
 */

namespace app\libraries\memory;
use app\libraries\database\Query;
use app\libraries\memory\datablocks\DataBlock;
use app\libraries\memory\datablocks\DataBlockManager;
use app\libraries\memory\tags\DataTagManager;
use app\libraries\memory\tags\TagCollection;
use app\libraries\memory\types\Type;
use app\libraries\memory\types\TypeCategory;
use app\libraries\memory\tags\DataTag;
use PDO;


/**
 * Class MemoryDataManager
 * @package app\libraries\memory
 */
class MemoryDataManager
{

    private static $memoryObject;
    /**
     * @var DataTag[]
     */
    public $tags = [];
    /**
     * @var DataTag[][]
     */
    public $tagsByName = [];
    /**
     * first index is name, second index is parent_id
     * @var DataTag[][] $tagsByNameAndParentId
     */
    public $tagsByNameAndParentId = [];
    /**
     * @var DataTag[][]
     */
    public $tagsByParentId = [];
    public $datablocks = [];
    public $datablocksByType = [];
    /**
     * @var DataTag[][]
     */
    public $referencesByBlockId = [];
    /**
     * @var DataBlock[][]
     */
    public $referencesByTagId = [];
    public $types = [];
    /**
     * @var Type[]
     */
    public $typesByName = [];
    /**
     * @var TypeCategory[]
     */
    public $type_categories = [];
    /**
     * @var TypeCategory[]
     */
    public $type_categoriesByName = [];
    public $dataTagManager;
    public $dataBlockManager;

    /**
     * MemoryDataManager constructor.
     */
    public function __construct()
    {
        ini_set('memory_usage', -1);
        $this->loadWithPDO();
        $this->dataTagManager = new DataTagManager($this);
        $this->dataBlockManager = new DataBlockManager($this);
    }

    private function loadWithPDO()
    {
        $pdo = Query::getPDO();

        $type_categories = $pdo->query('select * from type_categories where deleted_at IS NULL')->fetchAll(PDO::FETCH_ASSOC);
        foreach($type_categories as $type_category)
        {
            $category = new TypeCategory($type_category['id'], $type_category['name'], $type_category['updated_at'], $type_category['created_at']);;
            $this->type_categories[$category->get_id()] =  $category;
            $this->type_categoriesByName[$category->getName()] = $category;
        }


        $types =  $pdo->query('select * from types where deleted_at IS NULL')->fetchAll(PDO::FETCH_ASSOC);
        foreach($types as $type)
        {
            $type = new Type($type['id'], $type['name'], $this->type_categories[$type['type_category_id']] , $type['updated_at'], $type['created_at']);
            $this->types[$type->get_id()] = $type;
            $this->typesByName[$type->getName()] = $type;
        }



        $tags = $pdo->query("select * from tags where deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);
        foreach($tags as $tag)
        {
            $dataTag = new DataTag($tag['id'],$tag['name'], $tag['parent_tag_id'], $this->types[$tag['type_id']], $tag['sort_number'],$tag['updated_at'], $tag['created_at']);
            $this->tags[ $dataTag->get_id() ] = $dataTag;
            if(!isset($this->tagsByName[ $dataTag->get_name() ] ) )
                $this->tagsByName[ $dataTag->get_name() ] = [];
            $this->tagsByName[$dataTag->get_name()][] = $dataTag;
            if(!isset($this->tagsByParentId[ $tag['parent_tag_id'] ] ) )
                $this->tagsByParentId[ $tag['parent_tag_id'] ] = [];
            $this->tagsByParentId[ $tag['parent_tag_id'] ][] = $dataTag;

            if(!isset($this->tagsByNameAndParentId[ $dataTag->get_name()] ) )
                $this->tagsByNameAndParentId[ $dataTag->get_name()] = [];
            if(!isset($this->tagsByNameAndParentId[ $dataTag->get_name()][$tag['parent_tag_id'] ] ) )
                $this->tagsByNameAndParentId[ $dataTag->get_name()][$tag['parent_tag_id']] = $dataTag;
        }
        foreach($this->tags as $tag)
        {
            $parent = isset($this->tags[DataTag::getParentIdForConstruction($tag)]) ? $this->tags[DataTag::getParentIdForConstruction($tag)] : null;
            DataTag::addParentSafetly($tag,$parent);
            if(isset($parent))
                DataTag::addChildSafetly($parent, $tag);
        }


        $references = $pdo->query("select * from tags_reference where deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);
        foreach($references as $reference)
        {
            if(!isset( $this->referencesByBlockId[$reference['data_block_id'] ] ))
                $this->referencesByBlockId[$reference['data_block_id']] = [];
            $this->referencesByBlockId[$reference['data_block_id'] ][ ] = $this->tags[$reference['tag_id']];

        }

        $datablocks = $pdo->query("select * from data_blocks where deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);
        foreach($datablocks as $block)
        {
            $datablock = new DataBlock($block['id'], $this->types[$block['type_id']], new TagCollection($this->referencesByBlockId[$block['id']]),  $block['value'] );
            $this->datablocks[$block['id']] = $datablock;
            if(!isset($this->datablocksByType[$block['type_id']]))
                $this->datablocksByType[$block['type_id']] = [];
            $this->datablocksByType[$block['type_id']][] = $datablock;
            $tags = $this->referencesByBlockId[$block['id']];
            foreach($tags as $tag)
            {
                if(!isset( $this->referencesByTagId[ $tag->get_id() ]))
                    $this->referencesByTagId[$tag->get_id()] = [];
                $this->referencesByTagId[$tag->get_id()][] = $datablock;
            }
        }
    }

    public static function initialize()
    {
        self::$memoryObject = new MemoryDataManager();
    }

    /**
     * @return MemoryDataManager
     */
    public static function getInstance()
    {
        if(!isset(self::$memoryObject))
            self::$memoryObject = new MemoryDataManager();
        return self::$memoryObject;
    }



}