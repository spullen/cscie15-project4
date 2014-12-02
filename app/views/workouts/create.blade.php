@extends('layouts.main')

@section('content')
  {{ Form::model($workout, ['method' => 'post', 'action' => 'WorkoutsController@store', 'class' => 'form-horizontal']) }}
    <div class="form-group {{ $errors->has('activity_id') ? 'has-error' : '' }}">
      {{ Form::label('activity_id', 'Activity Type', array('class' => 'control-label col-md-2')) }}
      <div class="col-md-6">
        {{ Form::select('activity_id', WorkoutHelpers::activities(), $workout->activity_id, array('class' => 'form-control')) }}
        {{ $errors->first('activity_id', '<span class="help-block">:message</span>') }}
      </div>
    </div>
    <div class="form-group {{ $errors->has('metric') ? 'has-error' : '' }}">
      {{ Form::label('metric', 'Metric', array('class' => 'control-label col-md-2')) }}
      <div class="col-md-6">
        {{ Form::select('metric', WorkoutHelpers::metrics(), $workout->metric, array('class' => 'form-control')) }}
        {{ $errors->first('metric', '<span class="help-block">:message</span>') }}
      </div>
    </div>
    @include('workouts._form')
    <div class="form-group">
      <div class="col-md-offset-2 col-md-6">
        {{ Form::submit('Create', array('class' => 'btn btn-lg btn-primary')) }}
      </div>
    </div>
  {{ Form::close() }}
@stop