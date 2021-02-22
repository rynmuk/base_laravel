@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['role.role.update', $model->id] : 'role.role.store',
    'method'=> $method,
]) !!}

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="code" class="control-label">Code</label>
                {!! Form::text('code', null, ['class'=>'form-control', 'id'=>'code', 'maxlength'=>5]) !!}
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <label for="role" class="control-label">Role</label>
                {!! Form::text('role', null, ['class'=>'form-control', 'id'=>'role']) !!}
            </div>
        </div>
    </div>

{!! Form::close() !!}