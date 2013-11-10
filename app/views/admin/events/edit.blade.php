@section('header')

	<h1>Edit {{ trans_choice('global.modules.'.$model->view, 1) }}</h1>

@stop


@section('main')

	{{ Former::vertical_open()->method('PATCH')->action('admin/'.$model->view.'/'.$model->id)->role('form')->class('col-sm-6') }}

		@include('admin.'.$model->view.'._form')

	{{ Former::close() }}


	<div class="col-sm-6">
	{{ $files }}
	</div>

@stop