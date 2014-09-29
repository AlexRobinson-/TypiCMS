@section('js')
    {{ HTML::script(asset('js/admin/list.js')) }}
@stop

@include('admin._buttons-form')

{{ BootForm::hidden('id'); }}

<div class="row">

    <div class="col-sm-6">

        <div class="list-form" lang="{{ Config::get('app.locale') }}">

            @include('admin._buttons-list')

            <ul class="list-main nested sortable" data-url="/admin/menus/{{ $model->id }}/menulinks">
            @foreach ($model->menulinks->nest() as $menulink)
                @include('menulinks.admin._listItem', array('model' => $menulink))
            @endforeach
            </ul>

        </div>

    </div>


    <div class="col-sm-6">

        <div class="form-group @if($errors->has('class'))has-error @endif">
        {{ Form::label('side', trans('validation.attributes.side'), array('class' => 'control-label')) }}
        {{ Form::select('side', ['Front office' => trans('validation.attributes.Front office'), 'Back office' => trans('validation.attributes.Back office')], null, array('class' => 'form-control')) }}
        </div>

        <div class="form-group @if($errors->has('name'))has-error @endif">
            {{ Form::label('name', trans('validation.attributes.name'), array('class' => 'control-label')) }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
            {{ $errors->first('name', '<p class="help-block">:message</p>') }}
        </div>

        <div class="form-group @if($errors->has('class'))has-error @endif">
            {{ Form::label('class', trans('validation.attributes.class'), array('class' => 'control-label')) }}
            {{ Form::text('class', null, array('class' => 'form-control')) }}
            {{ $errors->first('class', '<p class="help-block">:message</p>') }}
        </div>

        @include('admin._tabs-lang')

        <div class="tab-content">

            @foreach ($locales as $lang)

            <div class="tab-pane fade @if ($locale == $lang)in active @endif" id="{{ $lang }}">
                <div class="form-group">
                    {{ Form::label($lang.'[title]', trans('validation.attributes.title')) }}
                    {{ BootForm::text(trans('labels.title'), $lang.'[title]')->autofocus('autofocus') }}
                </div>
                {{ BootForm::checkbox(trans('labels.online'), $lang.'[status]') }}
            </div>

            @endforeach

        </div>
        
    </div>

</div>
