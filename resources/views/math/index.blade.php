<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 6/30/2015
 * Time: 4:52 PM
 */
?>

@extends('layouts.default')


 @section('content')
<h1>Evaluator</h1>
<p>
Answer:  {{$answer }}
</p>
{{Form::open(array('url' => 'math', 'method' => 'POST'))}}

<p>
{{Form::label('variables', 'Equation:')}}<br/>
{{Form::Text('variables', $variables)}}
</p>


<p>
{{Form::label('equation', 'Equation:')}}<br/>
{{Form::Text('equation', $equation)}}
</p>

<p>{{Form::submit('Evaluate')}}</p>

{{Form::close()}}


 @endsection