<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 10:12 PM
 */use app\libraries\datablocks\DataBlock;use app\libraries\tags\DataTag;
/**
 * @var \app\libraries\excel\ExcelView $sheet
 */
$tag = $sheet->getParentTag();

?>
@if($sheet->hasData())
<div class="editor">

    <table class="sheet_editor" parent="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}">
        <thead>
        <tr>
            <th></th>
            @foreach($sheet->getHeaderTags() as $column )
                <th class="tag_column tag" tag="{!!$column->get_id()  !!}" >{!! $column->get_name() !!}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach($sheet->getRows() as $y => $row)
                <?php
                        $tags = $sheet->getTagsForRow($y);
                        $tagDelimited = $sheet->getCommaDelimitedTagsForRow($y)
                ?>
                <tr class="tag_row" sort_number="{!! $y !!}">
                    <td class="row_head" tags="{!! $tagDelimited !!}">
                        <div class="tags">
                            @foreach($tags as $tag)
                                <div class="tag"  tag="{!! $tag->get_id() !!}" >{!! $tag->get_name() !!}</div>
                            @endforeach
                        </div>
                        <div class="sort_number">{!! $y !!}</div>

                    </td>

                    @foreach($sheet->getHeaderTags() as $column)
                        <?php
                            /** @var DataTag $column */
                        $cell  = $sheet->getCell($column->get_sort_number(),$y);
                        ?>

                        @if(isset($cell))
                            <td class="cell">
                                <input type="text" class="form-control input-sm" datablock="{!! $cell->get_id() !!}" value="{!! $cell->getValue() !!}" />
                            </td>
                        @else
                            <td class="cell"><input type="text" class="form-control input-sm"  /></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif