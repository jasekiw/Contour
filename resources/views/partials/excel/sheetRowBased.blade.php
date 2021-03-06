<?php
use app\libraries\tags\collection\TagCollection;
use app\libraries\tags\DataTag;
use app\libraries\types\Types;
/** @var \app\libraries\excel\ExcelView $sheet */
$tag = $sheet->getParentTag();

?>
<table class="sheet_editor" orientation="row" parent="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}" @if(!$sheet->isInitialized()) unloaded="true" @endif >

@if($sheet->isInitialized())
    <thead>
    <tr>
        <th></th>
        @foreach($sheet->getRows() as $y => $row)
            <?php
            $tags = $sheet->getTagsForRow($y);
            $tagCollection =  new TagCollection($tags);
            $tagCollection->removeByTypes(array(Types::getTagPrimary()));
            $tags = $tagCollection->getAsArray(TagCollection::SORT_TYPE_NONE);

            $tagDelimited = $sheet->getCommaDelimitedTagsForRow($y)
            ?>
            <th class="tag_column tags" tags="{!! $tagDelimited !!}" sort_number="{{$y}}" >
                <div class="tags">
                    @foreach($tags as $tag)
                        <div class="tag"  tag="{!! $tag->get_id() !!}" >{!! $tag->get_name() !!}</div>
                    @endforeach
                </div>
                <div class="sort_number GeneralListHandle">{!! $y !!}</div>
            </th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach($sheet->getHeaderTags() as $x => $columnTag)
        <tr class="tag_row" sort_number="{!! $columnTag->get_sort_number() !!}" tag="{!! $columnTag->get_id() !!}">
            <td class="row_head">

                <div class="tag primary"  tag="{!! $columnTag->get_id() !!}" >{!! $columnTag->get_name() !!}</div>
            </td>
            @foreach($sheet->getRows() as $y => $row)
                <?php
                /** @var DataTag $column */
                $cell  = $sheet->getCell($columnTag->get_sort_number(),$y);
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

