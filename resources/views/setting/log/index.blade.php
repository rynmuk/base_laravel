@extends('base.main')
@section('title') Log Activity @endsection
@section('page_icon') <i class="fa fa-history"></i> @endsection
@section('page_title') Log Activity @endsection
@section('page_subtitle') list @endsection


@section('content')
    <div class="box box-solid">
        <div class="box-body">
            <table id="datatable" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">User</th>
                        <th width="20%">Subject</th>
                        <th width="10%">URL</th>
                        <th width="5%">Method</th>
                        <th width="10%">IP Address</th>
                        <th>Agent</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.table = $('#datatable').DataTable({
            responsive : true,
            processing : true,
            serverSide : true,
            ajax: "{{ route('table.log-activity') }}",
            columns: [
                {data : 'DT_RowIndex', name : 'id'},
                {data : 'user_id', name : 'user_id'},
                {data : 'subject', name : 'subject'},
                {data : 'url', name : 'url'},
                {data : 'method', name : 'method'},
                {data : 'ip_address', name : 'ip_address'},
                {data : 'agent', name : 'agent'}
            ]
        });

        setInterval( function () {
            table.ajax.reload( null, false ); // user paging is not reset on reload
            // reloadWidget();
        }, 10000 );
    </script>
@endpush