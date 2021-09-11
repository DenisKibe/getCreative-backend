@extends('layouts.app')

@section('content')

	<div class="panel-body">
	<!--display validation Errors-->
		@include('common.errors')

		<!--New Task Form-->
		<form action="{{ url('task') }}" method="POST" class="form-horizontal">
		{!! csrf_field() !!}

		<!--Task Name-->
		<div class="form-group">
			<label for="task-name" class="col-sm-3 control-label">Task</label>

			<div vlass="col-sm-6">
				<input type="text" name="name" id="task-name" class="form-control">
			</div>
		</div>
		<!--AddTask Button-->
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-6">
				<button type="submit" class="btn btn-default">
					<i class="fa fa-plus"></i> Add Task
				</button>
			</div>
		</div>
		</form>
	</div>

	<!-- current Tasks-->
	@if (count($tasks)>0)
		<div class="panel panel-default">
			<div class="panel-heading">
				Current Tasks
			</div>

			<div class="panel-body">
				<table class="table table-striped task-table">
					<!--Table Headings-->
					<thead>
						<th>Task</th>
						<th>$nbsp;</th>
					</thead>

					<!--table Body-->
					<tbody>
						@foreach($tasks as $task)
							<tr>
								<!--Task Name-->
								<td class="table-text">
									<div>{{ $task->name}}</div>
								</td>

								<td>
									<!--Delete Button -->
									<form action="{{ url('task/'.$task->id) }}" method="POST">
										{!! csrf_field() !!}
										{!! method_field('DELETE') !!}
										<button>Delete Task</button>
									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
@endsection
