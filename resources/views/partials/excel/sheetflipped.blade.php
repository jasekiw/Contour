<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 10:12 PM
 */
use app\libraries\tags\collection\TagCollection;use app\libraries\tags\DataTag;use app\libraries\types\Types;
/**
 * @var \app\libraries\excel\ExcelView $sheet
 */
$tag = $sheet->getParentTag();

?>
<div class="editor">
    <table class="sheet_editor" orientation="row" parent="{!! $tag->get_id() !!}" name="{!! $tag->get_name() !!}">
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
                <th class="tag_column tags" tags="{!! $tagDelimited !!}" >
                    <div class="tags">
                        @foreach($tags as $tag)
                            <div class="tag"  tag="{!! $tag->get_id() !!}" >{!! $tag->get_name() !!}</div>
                        @endforeach
                    </div>
                    <div class="sort_number">{!! $y !!}</div>
                </th>
            @endforeach

        </tr>
        </thead>
        <tbody>
        @foreach($sheet->getHeaderTags() as $x => $columnTag)
            <?php


            ?>
            <tr class="tag_row" sort_number="{!! $columnTag->get_sort_number() !!}">
                <td class="row_head">

                    <div class="tag"  tag="{!! $columnTag->get_id() !!}" >{!! $columnTag->get_name() !!}</div>
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

    </table>
</div>
