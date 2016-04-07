<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 4/6/2016
 * Time: 11:58 PM
 */

/**
 * @var \app\libraries\excel\ExcelProperties $propertiesView
 */

?>
<div class="editor">

<table class="properties_editor">
    <tbody>

        @foreach($propertiesView->getProperyTags() as $key => $tag)
            <?php $cell = $propertiesView->getPropertyValue($key); ?>
            <tr class="property" tag="{!! $tag->get_id() !!}">
                <td class="tag">
                    {!! $tag->get_name() !!}
                </td>
                <td class="datablock">
                @if(isset($cell))
                    <input class="form-control input-sm" type="text" datablock="{!! $cell->get_id() !!}" value="{!! $cell->getValue() !!}"/>
                @else
                    <input class="form-control input-sm" type="text" value="" />
                @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
