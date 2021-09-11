@extends('layouts.app')

@section('content')

	<!--Bootstrap Boilerplate-->
	<div class="panel-body">
	<!--Display validation Erors-->
		@include('common.errors')

		<!--New Task Form-->
		<form action="{{url('edit/'.'mNmmOpbtjq0mPsu') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            {!! csrf_field() !!}
            //{!! method_field('DELETE') !!}

			<!--Task Name-->
			<div class="form-group">
				<label for="task" class="col-sm-3 control-label">Task</label>

				<div class="col-sm-6">
					<input type="text" name="article_identifier" id="task-name" class="form-control">
				</div>
            </div>

            <!--Text-->
			<div class="form-group">
				<label for="task" class="col-sm-3 control-label">Task</label>

				<div class="col-sm-6">
					<input type="text" name="newText" id="task-text" class="form-control">
				</div>
            </div>

            <!--category-->
			<div class="form-group">
				<label for="task" class="col-sm-3 control-label">Task</label>

				<div class="col-sm-6">
					<input type="text" name="newTitle" id="task-categoryId" class="form-control">
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

