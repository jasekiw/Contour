<?php
use app\libraries\tags\DataTag;
/** @var \app\libraries\excel\ExcelView $sheet */
$tag = $sheet->getParentTag();

?>
<table class="sheet_editor" orientation="column" parent="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}" @if(!$sheet->isInitialized()) unloaded="true" @endif >
@if($sheet->isInitialized())
    <thead>
    <tr>
        <th></th>
        @foreach($sheet->getHeaderTags() as $column )
            <th class="tag_column tag primary" tag="{!!$column->get_id()  !!}" sort_number="{!! $column->get_sort_number() !!}">{!! $column->get_name() !!}</th>
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
                <div class="sort_number GeneralListHandle">{!! $y !!}</div>
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
@endif
</table>

