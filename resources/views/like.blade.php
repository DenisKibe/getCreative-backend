@extends('layouts.app')

@section('content')

	<!--Bootstrap Boilerplate-->
	<div class="panel-body">
	<!--Display validation Erors-->
		@include('common.errors')

		<!--New Task Form-->
		<form action="{{url('likes') }}" method="POST" class="form-horizontal">
			{!! csrf_field() !!}



            <!--category-->
			<div class="form-group">
				<label for="task" class="col-sm-3 control-label">Task</label>

				<div class="col-sm-6">
					<input type="text" name="article_id" id="task-article-id" class="form-control">
				</div>
			</div>

			<!--Add Task Button-->
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<button type="submit" class="btn btn-default">
						<i class="fa fa-plus"></i>Add Task
					</button>
				</div>
			</div>
		</form>
	</div>
	<!--TODO: current Tasks-->


@endsection

