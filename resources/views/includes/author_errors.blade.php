<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/1/2015
 * Time: 2:11 PM
 */
 ?>

 @if($errors->has())
 <ul>
 {!!$errors->first('name', '<li>:message</li>')!!}
 {!!$errors->first('bio', '<li>:message</li>')!!}
 </ul>

 @endif