<center>
    <H2 style="margin-bottom:10px">
        PEMERINTAH KABUPATEN BENGKALIS<br>
        DINAS PERKEBUNAN<br>
    </H2>
    <hr style="border-top: 1px solid #000;margin:0;padding:0">
    <hr style="border-bottom: 2px solid #000;margin:0;padding:0">
</center>
<br>
<style>
    table {
        border-width: 2px;
        border-collapse: collapse;
        border-style: solid;
    }

    table tr td {
        border-width: 2px;
        border-collapse: collapse;
        border-style: solid;
        border-color:#000;
    }
</style>
<table border="1" style="border-collapse: collapse;width: 100%;border-bottom: none !important;">
    <tr>
        <td align="center" colspan="2" style="border-bottom:none !important">
            <h3 style="line-height: normal;padding:5px 0;margin:0;">LEMBAR DISPOSISI</h3>
        </td>
    </tr>
    <tr>
        <td style="width:50%;border-bottom:none;vertical-align: top;padding-left:5px">Surat dari : {{ $data->surat_dari }}<br>
            No. Surat : {{ $data->nomor_surat }}
            <br>
            Tgl. Surat : {{ $data->tanggal_surat->translatedFormat('d F Y') }}
        </td>
        <td style="width:50%;border-bottom:none;vertical-align: top;padding:0 0 5px 5px">Diterima Tgl :  {{ $data->tanggal_terima->translatedFormat('d F Y') }}
            <br>
            No. Agenda : {{ $data->nomor_agenda }}<br>
            Sifat : <br>
            @foreach(['Segera','Sangat Segera','Rahasia'] as $sifat)
            @if(str($sifat)->upper()==$data->sifat)
            <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;">✔</span>
            @else 
            <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;color:#fff">✔</span>
            @endif
             {{ $sifat }}
        
            @endforeach
            
        </td>
    </tr>


</table>

<table border="1"
    style="border-collapse: collapse;width: 100%;border-top:none !important;border-bottom: none !important;">

    <tr>
        <td style="width:15%;height: 100px;vertical-align: top;border-bottom:none !important;padding-left:5px">Hal </td>
        <td style="vertical-align: top;border-bottom:none">: {{ $data->hal }}</td>
    </tr>
</table>
<style>
        ul {
        list-style-type: none;
        padding-left: 0;
      
    }

</style>

<table border="1" style="border-collapse: collapse;width: 100%;border-top:none !important">

    <tr>
        <td style="width:50%;padding:0 0 10px 5px;vertical-align: top">Diteruskan kepada Sdr :
            <ul class="checklist" style="margin:0;padding:0 0 0 10px;list-style:none">
                @foreach($penerima as $row)
                <li>
                    @if(in_array($row->id,$data->disposisis->pluck('pejabat_id')->toArray()))
                    <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;">✔</span>
                    @else 
                    <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;color:#fff">✔</span>
                    @endif
                    {{ $row->jabatan }} </li>
                @endforeach
            </ul>
        </td>
        <td style="padding:0 0 10px 5px">
            Dengan hormat harap :
            <ul class="checklist" style="margin:0;padding:0 0 0 10px;list-style:none">
                @foreach(['Tanggapan dan Saran','Proses Lebih lanjut','Koordinasi / Konfirmasikan'] as $row)
                <li>
                    @if(in_array($row,$data->harapan))
                    <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;">✔</span>
                    @else 
                    <span style="border:1px solid #000;  font-family: DejaVu Sans, sans-serif;color:#fff">✔</span>
                    @endif
                    {{ $row }} </li>
                @endforeach
            </ul>
        </td>
    </tr>
    <tr>
        <td  style="vertical-align: top;border-right:none !important;padding-left:5px">
            Catatan :
        <ul style="list-style-type: disc;padding-left:20px">
            <li>Kepala Dinas <br> <i>{{ $data->catatan ?? '-' }}</i> </li>
            @foreach($data->disposisis as $row)
            <li style="margin-top:10px">{{ $row->pejabat->jabatan }}<br><i>{{ $row->catatan }}</i></li>
            @endforeach
        </ul>
      
        </td>
        <td style="vertical-align: bottom;border-left:none !important">
            <div class="ttd" style="margin-top:150px;padding:20px">
                KEPALA DINAS PERKEBUNAN<br>KABUPATEN BENGKALIS<br>
                <br>
                <br>
                dto
                <br>
                <br>
                <br>
                MOHAMMAD AZMIR, S.Hut.T, M.Sc<br>
                Pembina TK.I<br>
                NIP. 19780613 199803 1 004
            </div>
        </td>
    </tr>
</table>