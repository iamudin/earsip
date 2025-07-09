<style>
    table td,th {border:1px solid #000}
</style>
<center>
    <img src="{{public_path('logokab.png')}}" style="float:left;height:70px;margin-right:10px">
    <h3 style="margin-top:10px;padding-top:0">AGENDA SURAT MASUK PADA DINAS PERKEBUNAN KABUPATEN BENGKALIS <br> {{str( $periode)->upper() }}</h3>
</center>
<br>
<table border="1" style="border-collapse: collapse;width:100%;font-size:small;border-color:#000">
    <thead>
        <tr>
            <th>No</th>
            <th>Pengirim</th>
            <th align="center">Tanggal Surat</th>
            <th>Nomor</th>
            <th>Perihal</th>
            <th align="center">Diterima Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $item->surat_dari }}</td>
                <td align="center">{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                <td>{{ $item->nomor_surat }}</td>
                <td>{{ $item->hal }}</td>
                <td align="center">{{ $item->tanggal_terima->format('d/m/Y') }}</td>
                <td>
                    @php 
                $status = null;
                if($item->disposisis->count()){
                $status .= 'Sudah disposisi ke : <br>';
                $status .= collect($item->disposisis)->map(function($item) {
                    return strtoupper($item->pejabat->jabatan); 
                })
                ->join(', ');
                }
                        else{
                            $status = 'Belum Disposisi';
                        }    
                    @endphp
                    {!! $status !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>