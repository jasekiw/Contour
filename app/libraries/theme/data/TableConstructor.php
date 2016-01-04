<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 9/11/2015
 * Time: 10:05 PM
 */

namespace app\libraries\theme\data;


use app\libraries\datablocks\staticform\DataBlocks;
use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use UserMeta;

class TableConstructor
{

    private static $progress = 0;
    private static $progress_max_value = 0;
    private static $start_queries;
    private static $end_queries;
    private static $readOnly = false;

    /**
     * @param DataTag $parent
     * @param DataTag[] $columns
     * @param bool $caculated
     */
    public static function printChildColumns($parent, $columns, $calculated = false)
    {





        if($parent->has_children())
        {
            foreach($parent->get_children()->getAsArray() as $child)
            {
                $number_deep = $parent->get_layers_deep_to_sheet();
                $spaces = "";
                for($i = 0; $i < $number_deep; $i++)
                {
                    $spaces .= '<i class="fa fa-long-arrow-right child_seperator"></i>';
                }
                echo '<tr>';
                echo '<td class="column_name_container">';
                echo $spaces ;
                echo "<div class='column_name' tagId='" . $child->get_id() . "'  sort_number='" . $child->get_sort_number() . "' >" . $child->get_name() . "</div>";
                echo '</td>';
                foreach($columns as $column)
                {
                    /**
                     * @var DataTag $column
                     */
                    echo '<td class="cell">';



                    $datablock = null;
                    if($calculated)
                        $datablock = DataBlocks::getByTagsArray(array($child, $column));
                    else
                        $datablock = DataBlocks::getValueByTagsArray(array($child, $column));


                    //self::getByTagsArray(array($child, $column));
                    //$finish = sizeOf($GLOBALS['queries']);
                    //echo $finish - $start;
                    if(isset($datablock))
                    {
                        ?> <input type="text" class="form-control input-sm" datablock="<?php echo $calculated ? $datablock->get_id() : $datablock->id ?>" value="<?php echo  $calculated ? $datablock->getProccessedValue() : $datablock->value; ?>" > <?php
                    }
                    self::$progress += 1;

                    echo '</td>';
                }
               // UserMeta::save('tableLoading', self::$progress . "/" . self::$progress_max_value);
                echo '<tr/>';

                if($child->has_children())
                {
                    self::printChildColumns($child, $columns);
                }
            }
        }
    }


    /**
     * Prints the Excel Sheet onto the screen
     * @param $id
     * @param bool $caculated
     */
    public static function printTable($id, $calculated = false)
    {
       //\DB::connection()->enableQueryLog();
        //self::$start_queries = sizeof($GLOBALS['queries']);

        /** @var DataTag $sheet */
        $sheet = DataTags::get_by_id($id);

        $all_tags = $sheet->get_children()->getAsArray();

        $all_tags = $sheet->get_children()->getAsArray();

        $columns = array();
        $rows = array();
        $columnsSize = 0;
        $rowSize = 0;
        self::$progress = 0;


        foreach($all_tags as $tag)
        {
            if($tag->get_type()->getName() == Types::get_type_column()->getName())
            {
                $columnsSize++;
                $columnsSize += sizeOf($tag->get_children()->getAsArray());
                array_push($columns, $tag);
            }
            if($tag->get_type()->getName() == Types::get_type_row()->getName())
            {
                $rowSize++;
                $rowSize += sizeOf($tag->get_children()->getAsArray());
                array_push($rows, $tag);
            }
        }

        self::$progress_max_value = $columnsSize * $rowSize;

       // UserMeta::save('tableLoading', self::$progress . "/" . self::$progress_max_value);

        ?>
        <table class="excel_editor" sheet="<?php echo $sheet->get_id() ?>">
        <thead>
        <tr>
            <td>

            </td>
            <?php

            foreach($columns as $column)
            {
                /**
                 * @var DataTag $column
                 */
                echo '<td>';
                echo $column->get_name();
                echo '</td>';
            }

            ?>
        </tr>
        </thead>

        <tbody>
        <?php

        foreach($rows as $row)
        {
            /**
             * @var DataTag $row
             */

            echo '<tr>';
            echo '<td class="column_name_container">';
            echo "<div class='column_name' tagId='" . $row->get_id() . "'  sort_number='" . $row->get_sort_number() . "' >" . $row->get_name() . "</div>";
            echo '</td>';
            foreach($columns as $column)
            {

                /**
                 * @var DataTag $column
                 */
                echo '<td class="cell">';


                $datablock = null;
                if($calculated)
                    $datablock = DataBlocks::getByTagsArray(array($row, $column));
                else
                    $datablock = DataBlocks::getValueByTagsArray(array($row, $column));
                //\app\libraries\datablocks\DataBlock::getByTagsArray(array($row, $column));



                if(isset($datablock))
                {
                    ?> <input type="text" class="form-control input-sm" datablock="<?php echo $calculated ? $datablock->get_id() : $datablock->id ?>" value="<?php echo  $calculated ? $datablock->getProccessedValue() : $datablock->value; ?>" > <?php
                }
                echo '</td>';
                self::$progress += 1;

                if(self::$progress % 200 == 0)
                {
                    UserMeta::save('tableLoading', self::$progress . "/" . self::$progress_max_value);
                }
            }


            echo '<tr>';


            self::printChildColumns($row, $columns);






        }



        ?>
        </tbody>
        </table>
        <?php

    }
}