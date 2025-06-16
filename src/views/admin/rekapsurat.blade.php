<div class="col-lg-12">
    <div class="mt-2">
      <form action="">
      <div class="row mt-4">
        <div class="col-6">
          <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
              <input value="{{ request('tanggal_mulai',null) }}" onchange="if(this.value) {location.href='{{ earsip_route('earsip.dashboard') }}?tanggal_mulai='+this.value}" type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
            </div>
          </div>


        </div>
        <div class="col-6">
          <div class="form-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
              </div>
              <input value="{{ request('tanggal_akhir',null) }}" onchange="if(this.value) {location.href='{{ earsip_route('earsip.dashboard') }}?tanggal_mulai={{ request('tanggal_mulai',date('Y-m-d')) }}&tanggal_akhir='+this.value}" type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
            </div>
          </div>
        </div>
        <div class="col-lg-12 text-right">
          <button class="btn btn-primary btn-sm" name="cetak_rekap" value="true"> <i class=" fa fa-print"></i> Cetak</button>
        </div>
      </div>
    </form>
    </div>

  </div>
  <div class="col-lg-12 text-center mt-4 mb-3">

    <h4>AGENDA SURAT MASUK PADA DINAS PERKEBUNAN KABUPATEN BENGKALIS <br> {{ str($periode)->upper() }}</h4>
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
        </tr>
    </thead>

</table>
  </div>

<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function() {
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [],

            ajax: {
                method: "POST",
                url: "{{ earsip_route('surat-masuk.riwayat') }}",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.tanggal_mulai = "{{ request('tanggal_mulai',null) }}";
                    d.tanggal_akhir = "{{ request('tanggal_akhir',null) }}";
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
