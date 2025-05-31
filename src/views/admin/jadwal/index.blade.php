@extends('cms::backend.layout.app',['title'=>'Jadwal Roro'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-calendar-alt"></i> Jadwal Roro</h3>
</div>
<div class="col-lg-12">
    <form class="row">

        <div class="mb-3 col-md-10">
          <label class="form-label">Pilih bulan :</label>
          <select name="" class="form-control" id="">

            <option value="">--pilih--</option>
            @foreach($months as $month)
            <option value="">{{ $month->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3 col-md-2 align-self-end">
          <button class="btn btn-primary w-100" type="button"><i class="bi bi-check-circle-fill me-2"></i>Tampilkan</button>
        </div>
      </form>
</div>
<div class="col-lg-12">
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Tanggal</th>
            <th>Trip</th>
        </tr>
        <tr>
<th></th>
        </tr>
    </thead>
</table>
</div>
</div>
@endsection
