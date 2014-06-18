@section('js')
    {{ HTML::script(asset('js/admin/form.js')) }}
@stop

@include('admin._buttons-form')

{{ Form::hidden('id'); }}
{{ Form::hidden('group', 'db'); }}

<div class="form-group @if($errors->has('key'))has-error @endif">
    {{ Form::label('key', trans('validation.attributes.key'), array('class' => 'control-label')) }}
    {{ Form::text('key', null, array('class' => 'form-control', 'autofocus')) }}
    @if($errors->has('key'))
    <span class="help-block">{{ $errors->first('key') }}</span>
    @endif
</div>

{{ Form::label('translations', trans('validation.attributes.translations'), array('class' => 'control-label')) }}
@foreach ($locales as $lang)
    <div class="form-group @if($errors->has($lang.'.translation'))has-error @endif">
        <div class="input-group">
            <span class="input-group-addon">{{ strtoupper($lang) }}</span>
            {{ Form::text($lang.'[translation]', $model->translate($lang)->translation, array('class' => 'form-control')) }}
        </div>
        @if($errors->has($lang.'.translation'))
        <span class="help-block">{{ $errors->first($lang.'.translation') }}</span>
        @endif
    </div>
@endforeach
