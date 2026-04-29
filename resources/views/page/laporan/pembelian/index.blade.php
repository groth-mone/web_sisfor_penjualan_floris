  @extends('page/layout/app')

  @section('title','Laporan Pembelian')

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
              <li class="breadcrumb-item active" aria-current="page">Pembelian</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-xl-6 pb-4" style="background: white;box-shadow:2px 2px grey;">
          <form method="GET">
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
            <input type="" hidden="" value="{{request()->has('access') ? request()->input('access') : null}}" name="access">
            <button class="btn btn-info mt-4"><i class="fa fa-filter"></i> Tampilkan</button>
            <a href="{{route('laporan.pembelian',['access'=>request()->has('access') ? request()->input('access') : null])}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh"></i></a>
            <a href="{{route('export.pembelian',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'PDF','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;" class="btn btn-danger mt-4"><i class="bx bxs-file-pdf"></i></a>
          </form>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Laporan Pembelian
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1" class="">No. </th>
                  <th data-priority="3" class="">Kode</th>
                  <th data-priority="4" class="">Tanggal</th>
                  <th data-priority="5" class="">Supplier</th>
                  <th data-priority="6" class="">Jumlah</th>
                  <th data-priority="7" class="">Total Pembelian</th>
                  <th data-priority="8" class="">Keterangan</th>
                </tr>
              </thead>
              <tbody>
               @foreach($data as $dt)
               <tr>
                <td>{{$loop->index+1}}.</td>
                <td>{{$dt->kode_pembelian}}</td>
                <td>{{$dt->tanggal_pembelian}}</td>
                <td>{{$dt->nama_supplier}}</td>
                <td>{{$dt->jumlah_barang_dibeli}} bahan dibeli</td>
                <td>Rp. {{number_format($dt->total_pembelian,0,",",".")}}</td>
                <td>{{$dt->keterangan_pembelian}}</td>
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
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true
    });
  });
</script>
@endsection
