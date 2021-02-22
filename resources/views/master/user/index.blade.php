@extends('base.main')
@section('title') User @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') User @endsection
@section('page_subtitle') list @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <a href="{{ route('user.sys-user.create') }}" class="modal-show btn btn-success" title="Create Project">
                <i class="fa fa-plus"></i> Create
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="box box-solid">
        <div class="box-header">
              <h3 class="box-title">Data Table With Full Features </h3>
            </div>
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Fullname</th>
                        <th>Email</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.user') }}",
            columns: [
                {data : 'DT_RowIndex', name : 'id'},
                {data : 'name', name : 'name'},
                {data : 'email', name : 'email'},
                {data : 'created_by', name : 'created_by'},
                {data : 'created_at', name : 'created_at'},
                {data : 'action', name : 'action'}
            ]
        });
    </script>
@endpush

