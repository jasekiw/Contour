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
    <h3>{!! $propertiesView->getTemplateName() !!}</h3>
    <table class="properties_editor" parent="{!! $propertiesView->getParentTag()->get_id() !!}">
        <tbody>

            @foreach($propertiesView->getProperyTags() as $key => $tag)
                <?php $cell = $propertiesView->getPropertyValue($key); ?>
                <tr class="property" tag="{!! $tag->get_id() !!}">
                    <td class="tag" tag="{!! $tag->get_id() !!}">
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