<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 11:10 PM
 */
use app\libraries\excel\ExcelTable;

/**
 * @var ExcelTable $table
 */
$headers = $table->getheaders();
$tag = $table->getParentTag();

?>
@if($table->hasData())
<div class="editor">


    <table class="table_editor" parent="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}">
        <thead>
        <tr>

            @foreach($table->getheaders() as $column)
                <th class="tag_column tag" tag="{!!$column->get_id()  !!}" >{!! $column->get_name() !!}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            <?php
            $y = 0;
            $hasData =  $table->rowHasData($y)
            ?>
            @while($hasData)

                <tr class="tag_row" number="{!! $y !!}">
                    @foreach($table->getheaders() as $x => $column)
                        <?php
                        $cell  =  $table->getCell($x, $y);
                        ?>

                        @if(isset($cell))
                            <td class="cell"> <input type="text" class="form-control input-sm" datablock="{!! $cell->get_id() !!}"
                                                     value="{!! $cell->getValue() !!}" /></td>
                        @else
                            <td class="cell"> <input type="text" class="form-control input-sm"  /></td>
                        @endif
                    @endforeach
                </tr>
                <?php
                $y++;
                $hasData =  $table->rowHasData($y)
                ?>
            @endwhile
        </tbody>

    </table>
</div>
@endif


