@php
    $method = $model->exists ? 'PUT' : 'POST';
@endphp
{!! Form::model($model, [
    'route' => $model->exists ? ['user.sys-user.update', $model->id] : 'user.sys-user.store',
    'method'=> $method,
]) !!}
    <div class="row">

        <div class="col-md-12">

            <div class="form-group">
                <label for="name" class="control-label">Full Name*</label>
                {!! Form::text('name', null, ['class'=>'form-control', 'id'=>'name']) !!}
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="control-label">Email*</label>
                {!! Form::email('email', null, ['class'=>'form-control', 'id'=>'email']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_number" class="control-label">Phone Number*</label>
                {!! Form::number('phone_number', null, ['class'=>'form-control', 'id'=>'phone_number']) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="address" class="control-label">Address*</label>
                {!! Form::textarea('address', null, ['class'=>'form-control', 'id'=>'address']) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="password" class="control-label">Password*</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password" class="control-label">Confirm Password*</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
            </div>
        </div>

        
        <div class="col-md-6">
            <h3>Authentication</h3>
            <div class="form-group">
                <label for="project_name" class="control-label">Role*</label>
                {!! Form::select('role', [''=>'- Select -']+['administrator'=>'Administrator','employee'=>'Employee'], null, ['class'=>'form-control', 'id'=>'role']) !!}
            </div>
        </div>

        
    </div>

{!! Form::close() !!}
