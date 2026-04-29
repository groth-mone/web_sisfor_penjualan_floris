<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Export Laporan Pembelian</title>

  <!-- <link rel="stylesheet" href="{{asset('print.css')}}"> -->
</head>
<style type="text/css">
  @page {
    margin: 100px 25px;
  }

  header {
    position: fixed;
    top: -100px;
    left: 0px;
    right: 0px;
    height: 50px;
    font-size: 20px !important;
    text-align: center;
    line-height: 35px;
  }
  th,
  td {
    font-size: 13px; /* Adjust as needed */
  }

</style>
<body>
 <header>
  Adelia Florist <br>Laporan Pembelian<br>
  @if(!empty($_GET['awal']) AND !empty($_GET['akhir']))
  <small>Periode : {{$_GET['awal']}} - {{$_GET['akhir']}}</small>
  @else
  <small>Periode : Tahun {{date('Y')}}</small>
  @endif
</center>
</header>
<!-- <footer>
  <center>
    ARS NURSERY <br>Laporan Pembelian<br>
  </center>
</footer> -->
<main>
 <div class="card-body">
  <br>
  <table style="width: 100%;padding: 0;margin: 0;" cellpadding="5" cellspacing="0" border="1">
  <!--   <thead>
      
  </thead> -->
  <tbody>
    <?php $nul=0; ?>
    <?php $nul_kurang=0; ?>
    <?php $nul_lebih=0; ?>
    <?php  
    $nul_harga_profit = array('0','0','0');
    ?>
    @foreach($data as $dt)
    <?php  
    $detail = App\Models\Page\Pembelian::getLaporanDetail($dt->id_pembelian);
    $subtotal = $nul+=$dt->total_penjualan;
    ?>
    <tr style="background: #aaa;">
      <th data-priority="1" class="">No. </th>
      <th data-priority="3" class="">Kode</th>
      <th data-priority="4" class="">Tanggal</th>
      <th data-priority="5" class="">Supplier</th>
      <th data-priority="6" class="">Jumlah Bahan</th>
      <th data-priority="7" class="">Total Pembelian</th>
      <th data-priority="8" class="">Keterangan</th>
    </tr>
    <tr>
      <td>{{$loop->index+1}}.</td>
      <td>{{$dt->kode_pembelian}}</td>
      <td>{{$dt->tanggal_pembelian}}</td>
      <td>{{$dt->nama_supplier}}</td>
      <td>{{$dt->jumlah_barang_dibeli}} bahan dibeli</td>
      <td>Rp. {{number_format($dt->total_pembelian,0,",",".")}}</td>
      <td>{{$dt->keterangan_pembelian}}</td>
    </tr>
    <tr>
      <th colspan="8" align="center">Rincian Pembelian</th>
    </tr>
    <tr style="background: #eee;">
      <th></th>
      <th>Kode Bahan</th>
      <th>Nama Bahan</th>
      <th>Harga Beli</th>
      <th>Harga Jual</th>
      <th>Jumlah</th>
      <th>Total Harga</th>
    </tr>
    @foreach($detail as $det)
    <?php  
    $sub_beli = number_format($nul_harga_profit[0]+=$det->harga_beli,0,",",".");
    $sub_jual = number_format($nul_harga_profit[1]+=$det->harga_jual,0,",",".");
    ?>
    <tr>
      <td></td>
      <td>{{$det->kode_barang}}</td>
      <td>{{$det->nama_barang}}</td>
      <td>Rp. {{number_format($det->harga_beli,0,",",".")}}</td>
      <td>Rp. {{number_format($det->harga_jual,0,",",".")}}</td>
      <td>{{$det->jml_pembelian}} {{$det->satuan_barang}}</td>
      <td>Rp. {{number_format($det->harga_beli*$det->jml_pembelian,0,",",".")}}</td>
    </tr>
    @endforeach
    @endforeach
  </tbody>
</table>
@if(!empty($subtotal))
<h3>
  Subtotal Penjualan 
  @if(!empty($_GET['awal']) AND !empty($_GET['akhir']))
  Dari Tanggal : {{tanggal_indonesia($_GET['awal'])}} - {{tanggal_indonesia($_GET['akhir'])}}
  @else
  <!-- Semua Periode -->
  Periode : Tahun {{date('Y')}}
  @endif
  = Rp. {{number_format($subtotal,0,",",".")}}
  <hr>
  Harga Beli : Rp. {{$sub_beli ? $sub_beli : '0'}}<br>
  Harga Jual : Rp. {{$sub_jual ? $sub_jual : '0'}}<br>
</h3>
@endif
</div>
</main>
</body>
</html>
