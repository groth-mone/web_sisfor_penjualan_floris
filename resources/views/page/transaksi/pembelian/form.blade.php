   <div class="page-heading" hidden="" id="pageForm">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Transaksi</a></li>
              <li class="breadcrumb-item active" aria-current="page">Form Pembelian</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 modal-title"></h5>
      </div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data" id="pembelianForm">
          @csrf
          <div class="row">
            <label class="col-lg-4 col-form-label mb-2">Tanggal Pembelian <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="text" hidden="" id="id_pembelian" name="id_pembelian">
                <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian">
                <span class="invalid-feedback" role="alert" id="tanggal_pembelianError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <select style="width: 100%;" class="form-control" id="id_supplier" name="id_supplier">
                  @foreach($supplier as $sup)
                  <option value="{{$sup->id_supplier}}">{{$sup->nama_supplier}}</option>
                  @endforeach
                </select>
                <span class="invalid-feedback" role="alert" id="id_supplierError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Keterangan Pembelian <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <textarea class="form-control" rows="4" id="keterangan_pembelian" name="keterangan_pembelian"></textarea>
                <span class="invalid-feedback" role="alert" id="keterangan_pembelianError">
                  <strong></strong>
                </span>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="row mt-3">
            <div class="col-lg-12" id="tab_detail">
              <a class="nav-item nav-link active" style="width: 100%;" data-bs-toggle="tab" href="#tab-pane-1"><i class="bx bx-bed"></i> Pembelian Detail
                <span class="error-tab text-danger">Error</span>
              </a>
              <input type="" id="id_pembelian_detail_del" name="id_pembelian_detail_del" hidden="">
              <!-- gambar -->
              <button type="button" id="new_pembelian_detail" class="btn btn-info text-white btn-sm mb-2 mt-3">
                <i class="bx bx-plus"></i>
              </button>
              <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No. </th>
                      <th>Bahan</th>
                      <th>Satuan</th>
                      <th>Harga Beli</th>
                      <th>Harga Jual</th>
                      <th>Jumlah</th>
                      <th>Subtotal</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="table_pembelian_detail">
                  </tbody>
                </table>
                <h5 class="subtotal_view mt-4 mb-5"></h5>
              </div>
              <!-- end gambar -->
              <!-- fasilitas -->
              <!-- end fasilitas -->
              <!-- kamar -->
              <!-- end kamar -->
            </div>
          </div>
          <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
          <button type="button" class="btn close"> Kembali</button>
        </form>
        <!--  -->
        <div style="display:none;">
          <table id="sample_table_pembelian_detail">
            <tr id="">
              <td><span class="sn" style="vertical-align:middle;"></span></td>   
              <td>
                <input autocomplete="off" name="barang[0][id_pembelian_detail]" hidden="" id="barang_0_id_pembelian_detail" class="form-control form-control-sm id_pembelian_detail_input">
                <select name="barang[0][id_barang]" style="width: 100%;" id="barang_0_id_barang" class="form-control form-control-sm id_barang_input">
                  @foreach($barang as $brg)
                  <option value="{{$brg->id_barang}}" more_satuan="{{$brg->satuan_barang}}">{{$brg->nama_barang}}</option>
                  @endforeach
                </select>
                <span class="invalid-feedback id_barang_input_error" role="alert" id="barang_0_id_barangError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" name="barang[0][satuan_barang]" readonly="" id="barang_0_satuan_barang" class="satuan_barang_input form-control">
                <span class="invalid-feedback satuan_barang_input_error" role="alert" id="barang_0_satuan_barangError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" name="barang[0][harga_beli]" id="barang_0_harga_beli" class="harga_beli_input form-control">
                <span class="invalid-feedback harga_beli_input_error" role="alert" id="barang_0_harga_beliError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" name="barang[0][harga_jual]" id="barang_0_harga_jual" class="harga_jual_input form-control">
                <span class="invalid-feedback harga_jual_input_error" role="alert" id="barang_0_harga_jualError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="number" min="1" name="barang[0][jml_pembelian]" id="barang_0_jml_pembelian" class="jml_pembelian_input form-control">
                <span class="invalid-feedback jml_pembelian_input_error" role="alert" id="barang_0_jml_pembelianError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" readonly="" name="barang[0][total_harga]" id="barang_0_total_harga" class="total_harga_input form-control">
                <span class="invalid-feedback total_harga_input_error" role="alert" id="barang_0_total_hargaError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <center>
                  <button type="button" class="delete-pem-det btn btn-sm btn-danger" data-id="0"><i class="bx bx-x"></i></button>
                </center>
              </td>
            </tr>
          </table>
        </div>
        <!--  -->
      </div>
    </div>
  </div>
