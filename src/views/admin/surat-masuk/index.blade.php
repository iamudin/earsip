@extends('cms::backend.layout.app', ['title' => 'Surat Masuk'])
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3 style="font-weight:normal" class="pull-left"> <i class="fa fa-envelope"></i> Surat Masuk </h3>
            @if (earsip_user()?->pejabat?->alias_jabatan == 'OPERATOR')
                <a href="{{ earsip_route('surat-masuk.create') }}" class=" btn btn-primary btn-sm pull-right"> <i
                        class="fa fa-plus"></i> Tambah</a>
            @endif
        </div>

        <div class="col-lg-12 mt-2">
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item"><a href="#" class="nav-link {{ !request()->segment(2) ? 'active':'' }}" data-toggle="tab" onclick="location.href='{{ earsip_route('surat-masuk.index') }}'"> <i class="fa fa-download"></i>
                        Baru</a> </li>
                <li class="nav-item"><a  href="#" class="nav-link  {{ request()->segment(2) ? 'active':'' }}" data-toggle="tab" onclick="location.href='{{ earsip_route('surat-masuk.index.selesai') }}'"> <i class="fa fa-mail-forward"></i>
                        Sudah Disposisi </a></li>

            </ul>


            <table class="datatable table table-hover table-bordered bg-white" style="font-size:small;background:#fff">
                <thead>
                    <tr>
                        <th width="10px">No</th>
                        <th>Pengirim</th>
                        <th>Tgl Surat</th>
                        <th>Nomor</th>
                        <th>Perihal</th>
                        <th>Diterima Tgl</th>
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
                    url: "{{ earsip_route('surat-masuk.datatable') }}",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
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
                        data: 'surat_dari',
                        searchable: true,
                        name: 'surat_dari',
                        orderable: false
                    },

                    {
                        data: 'tanggal_surat',
                        searchable: true,
                        name: 'tanggal_surat',
                        orderable: false
                    },
                    {
                        data: 'nomor_surat',
                        searchable: true,
                        name: 'nomor_surat',
                        orderable: false
                    },

                    {
                        data: 'hal',
                        searchable: true,
                        name: 'hal',
                        orderable: false
                    },

                    {
                        data: 'tanggal_terima',
                        searchable: true,
                        name: 'tanggal_terima',
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
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    @endpush
    @push('scripts')
        <script type="text/javascript" src="{{ url('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js">
        </script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js">
        </script>
        <script type="text/javascript">
            $('#sampleTable').DataTable();
        </script>
        @include('cms::backend.layout.js')
    @endpush
@endsection
