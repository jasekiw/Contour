<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 4/6/2016
 * Time: 1:50 PM
 */

namespace app\libraries\excel;

use app\libraries\tags\DataTag;
use app\libraries\types\Types;
use app\libraries\tags\collection\TagCollection;

/**
 * Class ExcelView
 * @package app\libraries\excel
 */
class ExcelView
{
    
    /** @var DataTag   */
    public $parentTag;
    /** @var ExcelTable   */
    public $summaryTable;
    /** @var ExcelSheet   */
    public $summarySheet;
    /** @var ExcelSheet   */
    public $summaryHybrid;
    /** @var ExcelView[]   */
    public $composits = [];
    /** @var ExcelProperties   */
    public $propertysView;
    
    private $loopedChildren = false;
    
    /**
     * ExcelView constructor.
     *
     * @param DataTag        $tag
     */
    function __construct($tag)
    {
        $this->parentTag = $tag;
        $children = $this->parentTag->getSimpleChildren();
        $composits = $this->parentTag->getCompositChildren();
        $headers = $children->getTagWithTypesAsArray([Types::getTagPrimary()]);
        if (sizeof($headers) == 0 && is_array($paramHeaders))
            $headers = $paramHeaders;
       
        
        foreach($headers as $header)
        {
            $datablocks = $header->getDataBlocks();
            $tagCollection = $datablocks->getCommonTags();
            $tagCollection->remove($header->get_id());
            foreach($tagCollection->getAsArray(TagCollection::SORT_TYPE_NONE) as $tag)
            {

            }
        }
    }
    
    public function hasChildren()
    {
        return sizeof($this->composits) != 0;
    }
    
    /**
     * @return bool
     */
    public function hasloopedChildren()
    {
        return $this->loopedChildren;
    }
    
    public function setLoopedChildren()
    {
        $this->loopedChildren = true;
    }
    
    /**
     * @return ExcelTab[]
     */
    public function getNavTitles()
    {
        $navTitles = [];
        
        $stackframes = [['index' => 0, 'sheet' => $this]];
        $currentStack = 0;
        while (isset($stackframes[$currentStack])) {
            $index = &$stackframes[$currentStack]['index'];
            /**  @var ExcelView $sheet */
            $sheet = &$stackframes[$currentStack]['sheet'];
            
            if (!isset($sheet->composits[$index])) // if we got to the ned of the loop, pop the stack
            {
                array_push($navTitles, new ExcelTab($this->getNameStack($stackframes, $currentStack), $sheet));
                unset($stackframes[$currentStack]);
                $currentStack--;
                if (isset($stackframes[$currentStack]))
                    $stackframes[$currentStack]['index']++;
            } else {
                $currentStack++;
                $stackframes[$currentStack] = ['index' => 0, 'sheet' => $sheet->composits[$index]];
            }
        }
        return array_reverse($navTitles);
    }
    
    /**
     * @param array $stack
     * @param int   $currentStack
     *
     * @return string
     */
    private function getNameStack($stack, $currentStack)
    {
        $name = [];//$sheet->composits[$index]->get_name();
        $imaginaryIndex = $currentStack;
        while ($imaginaryIndex >= 0) {
            /** @var ExcelView $imaginarySheet */
            $imaginarySheet = $stack[$imaginaryIndex]['sheet'];
            array_unshift($name, $imaginarySheet->get_name());
            $imaginaryIndex--;
        }
        $name = implode("->", $name);
        return $name;
    }
    
    /**
     * @return string
     */
    public function get_name()
    {
        return $this->parentTag->get_name();
    }
    
}