<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Export Laporan Penjualan</title>

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
  Adelia Florist <br>Laporan Penjualan<br>
  @if(!empty($_GET['tanggal_awal']) AND !empty($_GET['tanggal_akhir']))
  <small>Periode : {{$_GET['tanggal_awal']}} - {{$_GET['tanggal_akhir']}}</small>
  @else
  <small>Periode : Tahun {{date('Y')}}</small>
  @endif
</center>
</header>
<main>
 <div class="card-body">
  <br>
  User Pengelola/Admin: 
  @if(count($user) > 0)
  @foreach($user as $usr)
  <b>{{$usr->name ?? '-'}}</b>
  @endforeach
  @else
  -
  @endif
  <br>
  <br>
  <table style="width: 100%;padding: 0;margin: 0;" cellpadding="5" cellspacing="0" border="1">
    <tbody>
      <?php $nul=0; ?>
      <?php $nul_kurang=0; ?>
      <?php $nul_lebih=0; ?>
      <?php  
      $nul_harga_profit = array('0','0','0');
      ?>
      @foreach($data as $dt)
      <?php  
      $result = App\Models\Page\Penjualan::getLaporanDetail($dt->id_penjualan);
      $detail = $result['detail'];
      $pembayaran = $result['pembayaran'];

      $subtotal = $nul+=$dt->total_penjualan;
      ?>
      <tr style="background: #aaa;">
       <th>No.</th>
       <th>Kode</th>
       <th>Tanggal</th>
       <th>Pelanggan</th>
       <th>Total Pesanan</th>
       <th>Total Dibayar</th>
       <th colspan="2">Keterangan</th>
     </tr>
     <tr>
       <td>{{$loop->index+1}}</td>
       <td><b>#{{$dt->kode_penjualan}}</b></td>
       <td>{{$dt->tanggal_penjualan}}</td>
       <td>{{$dt->pelanggan}}</td>
       <td>Rp. {{number_format($dt->total_penjualan,0,",",".")}}</td>
       <td>Rp. {{number_format($dt->total_pembayaran,0,",",".")}}</td>
       <td colspan="2">{{$dt->keterangan_penjualan}}</td>
     </tr>
     <tr>
      <th colspan="8" align="center">Rincian Penjualan</th>
    </tr>
    <tr style="background: #eee;">
      <th></th>
      <th>Kode Item</th>
      <th>Nama Item</th>
      <th>Harga Beli</th>
      <th>Harga Jual</th>
      <th>Jumlah</th>
      <th>Total Harga</th>
      <th>Profit</th>
    </tr>
    @foreach($detail as $det)
    <?php  
    $profit = ($det->harga_jual - $det->harga_beli) * $det->jml_penjualan;
    $sub_beli = number_format($nul_harga_profit[0]+=$det->harga_beli,0,",",".");
    $sub_jual = number_format($nul_harga_profit[1]+=$det->harga_jual,0,",",".");
    $sub_profit = number_format($nul_harga_profit[2]+=$profit,0,",",".");
    ?>
    <tr>
      <td></td>
      <td>{{$det->kode_barang}}</td>
      <td>{{$det->nama_barang}}</td>
      <td>Rp. {{number_format($det->harga_beli,0,",",".")}}</td>
      <td>Rp. {{number_format($det->harga_jual,0,",",".")}}</td>
      <td>{{$det->jml_penjualan}} {{$det->satuan_barang}}</td>
      <td>Rp. {{number_format($det->harga_jual*$det->jml_penjualan,0,",",".")}}</td>
      <td>Rp. {{ number_format(($det->harga_jual - $det->harga_beli) * $det->jml_penjualan, 0, ",", ".") }}</td>
    </tr>
    @endforeach
    <tr>
      <th colspan="8" align="center">Metode Pembayaran</th>
    </tr>
    <tr style="background: #eee;">
      <th></th>
      <th>Metode Pembayaran</th>
      <th>Nominal Pembayaran</th>
      <th colspan="5" align="left">Tanggal Pembayaran</th>
    </tr>
    @foreach($pembayaran as $pem)
    <tr>
      <td></td>
      <td>
        {{$pem->metode_pembayaran}}
        @if($pem->metode_detail != NULL)
        ({{$pem->metode_detail}})
        @endif
      </td>
      <td>Rp. {{number_format($pem->nominal_pembayaran,0,",",".")}}</td>
      <td colspan="5">
        @if($pem->tanggal_pembayaran == NULL)
        -
        @else
        {{$pem->tanggal_pembayaran}}
        @endif
      </td>
    </tr>
    @endforeach

    @endforeach
  </tbody>
</table>
@if(!empty($subtotal))
<h3>
  Subtotal Penjualan 
  @if(!empty($_GET['tanggal_awal']) AND !empty($_GET['tanggal_akhir']))
  Dari Tanggal : {{$_GET['tanggal_awal']}} - {{$_GET['tanggal_akhir']}}
  @else
  <!-- Semua Periode -->
  Periode : Tahun {{date('Y')}}
  @endif
  = Rp. {{number_format($subtotal,0,",",".")}}
  <hr>
  Harga Beli : Rp. {{$sub_beli ? $sub_beli : '0'}}<br>
  Harga Jual : Rp. {{$sub_jual ? $sub_jual : '0'}}<br>
  Profit : Rp. {{$sub_profit ? $sub_profit : '0'}}<br>
</h3>
@endif
</div>
</main>
</body>
</html>
