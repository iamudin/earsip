@extends('cms::backend.layout.app',['title'=>'Surat Masuk'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal" class="pull-left"> <i class="fa fa-envelope"></i> Surat Masuk

</h3>
<a href="{{ earsip_route('pejabat.create') }}" class=" btn btn-primary btn-sm pull-right"> <i class="fa fa-plus"></i> Tambah</a>
</div>

<div class="col-lg-12 mt-4">
<table class="datatable table table-striped table-bordered bg-white" style="font-size:small">
    <thead>
        <tr>
            <th width="10px">No</th>
            <th >NIP</th>
            <th >Nama</th>
            <th >Jabatan</th>
            <th >No.Hp</th>
            <th >Peran</th>
            <th>Status</th>
            <th style="width:10px">Aksi</th>
        </tr>
    </thead>

</table>
</div>
</div>
<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function() {
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [],

            ajax: {
                method: "POST",
                url: "{{ earsip_route('pejabat.datatable')}}",
                data: function (d){
                 d._token = "{{csrf_token()}}";
            }
            },
            lengthMenu: [10, 20, 50, 100, 200, 500],
            deferRender: true,
            columns: [

                {
                    className: 'text-center',
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                        data: 'nip',
                        searchable: true,
                        name: 'nip',
                        orderable: false
                },
                    {
                        data: 'nama',
                        searchable: true,
                        name: 'nama',
                        orderable: false
                },
                    {
                        data: 'jabatan',
                        searchable: true,
                        name: 'jabatan',
                        orderable: false
                },
                {
                    data: 'nohp',
                    searchable: true,
                    name: 'nohp',
                    orderable: false
                },
                   {
                    data: 'alias',
                    searchable: true,
                    name: 'alias',
                    orderable: false
                },
   {
                    data: 'status',
                    searchable: true,
                    name: 'status',
                    orderable: false
                },
                  {
                    data: 'action',
                    searchable: false,
                    name: 'action',
                    orderable: false
                },
            ],
            responsive: true,

        });


    });
</script>

@push('styles')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

@endpush
@push('scripts')
<script type="text/javascript" src="{{url('backend/js/plugins/jquery.dataTables.min.js')}}"></script>
     <script type="text/javascript" src="{{url('backend/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
     <script type="text/javascript">$('#sampleTable').DataTable();</script>
@include('cms::backend.layout.js')
@endpush

@endsection
