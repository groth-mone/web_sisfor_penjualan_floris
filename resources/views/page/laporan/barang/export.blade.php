<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Export Laporan Persediaan Bahan</title>

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
  Adelia Florist <br>Laporan Persediaan Bahan<br>
  @if(!empty($_GET['tanggal_awal']) AND !empty($_GET['tanggal_akhir']))
  <small>Periode : {{$_GET['tanggal_awal']}} - {{$_GET['tanggal_akhir']}}</small>
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
  <span>Kode: {{optional($barang->first())->kode_barang ?? '-'}}</span><br>
  <span>Bahan: {{optional($barang->first())->nama_barang ?? '-'}}</span>
  <br>
  <br>
  <table style="width: 100%;padding: 0;margin: 0;" cellpadding="5" cellspacing="0" border="1">
   <thead>
    <tr>
     <th data-priority="" class="" rowspan="2">No. </th>
     <th data-priority="" class="" rowspan="2">Tanggal</th>
     <th data-priority="" class="" rowspan="2">Kode Transaksi</th>
     <th data-priority="" class="" rowspan="2">Jenis</th>
     <th data-priority="" class="" colspan="3" style="text-align: center;">Pembelian</th>
     <th data-priority="" class="" colspan="3" style="text-align: center;">Penjualan</th>
     <th data-priority="" class="" colspan="3" style="text-align: center;">Persediaan</th>
   </tr>
   <tr>
     <th data-priority="" class="">QTY</th>
     <th data-priority="" class="">Harga</th>
     <th data-priority="" class="">Total Harga</th>
     <th data-priority="" class="">QTY</th>
     <th data-priority="" class="">Harga</th>
     <th data-priority="" class="">Total Harga</th>
     <th data-priority="" class="">Stok</th>
     <th data-priority="" class="">Harga</th>
     <th data-priority="" class="">Total Harga</th>
   </tr>
 </thead>
 <tbody>
  @php $counter = 1; @endphp
  @foreach($combined as $com)
  <tr>
    <td>{{ $counter++ }}</td>
    <td>{{ $com['tanggal'] }}</td>
    <td>{{ $com['kode'] }}</td>
    <td>{{ $com['jenis'] ?? '-' }}</td>
    <td>{{ $com['qty_pembelian'] ? $com['qty_pembelian'] : '-' }}</td>
    <td>{{ $com['harga_pembelian'] ? number_format($com['harga_pembelian'], 0, ',', '.') : '-' }}</td>
    <td>{{ $com['total_pembelian'] ? number_format($com['total_pembelian'], 0, ',', '.') : '-' }}</td>

    <td>{{ $com['qty_penjualan'] ? $com['qty_penjualan'] : '-' }}</td>
    <td>{{ $com['harga_penjualan'] ? number_format($com['harga_penjualan'], 0, ',', '.') : '-' }}</td>
    <td>{{ $com['total_penjualan'] ? number_format($com['total_penjualan'], 0, ',', '.') : '-' }}</td>

    <td>{{ $com['sisa_stok'] ? $com['sisa_stok'] : '-' }}</td>
    <td>{{ $com['harga_persediaan'] ? number_format($com['harga_persediaan'], 0, ',', '.') : '-' }}</td>
    <td>{{ $com['total_persediaan'] ? number_format($com['total_persediaan'], 0, ',', '.') : '-' }}</td>
  </tr>
  @endforeach
</tbody>
</table>
</div>
</main>
</body>
</html>
