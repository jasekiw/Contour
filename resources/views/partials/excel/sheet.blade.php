<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 10:12 PM
 */
/**
 * @var \app\libraries\excel\ExcelSheet $sheet
 */
$tag = $sheet->getParentTag();

?>
@if($sheet->hasData())
<div class="editor">
    <table class="sheet_editor" sheet="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}">
        <thead>
        <tr>
            <th></th>
            @foreach($sheet->getColumnTags() as $column)
                <th class="sheet_column tag" tag="{!!$column->get_id()  !!}" >{!! $column->get_name() !!}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach($sheet->getRowTags() as $y => $row)
                <tr class="sheet_row" tag="{!! $row->get_id() !!}">
                    <td class="row_name tag" tag="{!! $row->get_id() !!}" >{!! $row->get_name() !!}</td>
                    @foreach($sheet->getColumnTags() as $x => $column)
                        <?php
                        $cell  = $sheet->getCell($x,$y);
                        ?>

                        @if(isset($cell))
                            <td class="cell"> <input type="text" class="form-control input-sm" datablock="{!! $cell->get_id() !!}"
                                                     value="{!! $cell->getValue() !!}" /></td>
                        @else
                            <td class="cell"> <input type="text" class="form-control input-sm"  /></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif