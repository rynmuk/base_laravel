
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<tbody>
				
				<tr>
					<th>Fullname</th>
					<td>{{ $model->name }}</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>{{ $model->email }}</td>
				</tr>
				<tr>
					<th>Phone Number</th>
					<td>{{ $model->phone_number }}</td>
				</tr>
				<tr>
					<th>Addres</th>
					<td>{{ $model->address }}</td>
				</tr>
				<tr>
					<th>Role</th>
					<td>{{ ucwords($model->role) }}</td>
				</tr>
				
				<tr>
                    <th>Created by</th>
                    <td>{{ $uti->getUser($model->created_by) }}</td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td>{{ date('d-m-Y H:i:s', strtotime($model->created_at)) }}</td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td>{{ $uti->getUser($model->updated_by) }}</td>
                </tr>
                <tr>
                    <th>Updated Date</th>
                    <td>{{ date('d-m-Y H:i:s', strtotime($model->updated_at)) }}</td>
                </tr>

                <tr>
                    <th>Last Login</th>
                    <td>{{ auth()->user()->last_sign_in_at->diffForHumans() }}</td>
                </tr>
				
			</tbody>
		</table>
	</div>
	
</div>
