  @extends('page/layout/app')

  @section('title','Laporan Bahan')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Laporan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bahan</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-xl-6 pb-4" style="background: white;box-shadow:2px 2px grey;">
          <form method="GET">
            <div class="row mt-3">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Bahan</label>
              <div class="col-sm-8">
                <select class="form-control" id="barang" name="barang">
                  <option value="">.: PILIH BAHAN :.</option>
                  @foreach($barang as $brg)
                  <option value="{{ $brg->id_barang }}" 
                    @if(request()->get('barang') == $brg->id_barang) selected @endif>
                    {{ $brg->nama_barang }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Awal</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_awal') ? request()->input('tanggal_awal') : ''}}" class="form-control" name="tanggal_awal">
              </div>
            </div>
            <div class="row mt-3">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Akhir</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : ''}}" class="form-control" name="tanggal_akhir">
              </div>
            </div>
            <button class="btn btn-info mt-4"><i class="fa fa-filter"></i> Tampilkan</button>
            <a href="{{route('laporan.barang')}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh"></i></a>
            @if(!empty($_GET['barang']))
            <a href="{{route('export.barang',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','barang'=>request()->has('barang') ? request()->input('barang') : ''])}}" target="_blank" style="float: right;" class="btn btn-danger mt-4"><i class="bx bxs-file-pdf"></i></a>
            @endif
          </form>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Laporan Persediaan Bahan
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped datatable" border="1" cellpadding="0" cellspacing="0" style="width: 100%;">
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
      </div>
    </div>
  </section>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $(function () {
    $('.datatable').DataTable({
      // order: [[2, 'desc']],
      processing: true,
      pageLength: 10
    });
  });
</script>
@endsection
