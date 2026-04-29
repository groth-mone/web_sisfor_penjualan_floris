   <div class="page-heading" hidden="" id="pageForm">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Transaksi</a></li>
              <li class="breadcrumb-item active" aria-current="page">Form Penjualan</li>
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
        <form method="post" enctype="multipart/form-data" id="penjualanForm">
          @csrf
          <div class="row">
            <label class="col-lg-4 col-form-label mb-2">Tanggal <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="text" hidden="" id="id_penjualan" name="id_penjualan">
                <input type="date" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan">
                <span class="invalid-feedback" role="alert" id="tanggal_penjualanError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Pelanggan <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="text" class="form-control" id="pelanggan" name="pelanggan">
                <span class="invalid-feedback" role="alert" id="pelangganError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Nomor HP <span class="text-danger">*</span></label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="number" min="0" class="form-control" id="nomor_pelanggan" name="nomor_pelanggan">
                <span class="invalid-feedback" role="alert" id="nomor_pelangganError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Keterangan </label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <textarea class="form-control" rows="4" id="keterangan_penjualan" name="keterangan_penjualan"></textarea>
                <span class="invalid-feedback" role="alert" id="keterangan_penjualanError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Ongkir</label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="text" class="form-control ongkir_input" id="ongkir" name="ongkir" placeholder="Rp. 0">
                <span class="invalid-feedback" role="alert" id="ongkirError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Status Pengiriman</label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <select class="form-control select_opsi" id="status_pengiriman" name="status_pengiriman">
                  <option value="Pesanan Masuk">Pesanan Masuk</option>
                  <option value="Diproses">Diproses</option>
                  <option value="Dikirim">Dikirim</option>
                  <option value="Selesai">Selesai</option>
                </select>
                <span class="invalid-feedback" role="alert" id="status_pengirimanError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Alamat Pengiriman</label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <textarea class="form-control" rows="3" id="alamat_pengiriman" name="alamat_pengiriman"></textarea>
                <span class="invalid-feedback" role="alert" id="alamat_pengirimanError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Catatan Kuitansi</label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <textarea class="form-control" rows="2" id="catatan_kuitansi" name="catatan_kuitansi"></textarea>
                <span class="invalid-feedback" role="alert" id="catatan_kuitansiError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <label class="col-lg-4 col-form-label mb-2">Foto Pesanan</label>
            <div class="col-lg-8 mb-2">
              <div class="form-group">
                <input type="hidden" id="foto_pesanan_lama" name="foto_pesanan_lama">
                <input type="file" class="form-control" id="foto_pesanan" name="foto_pesanan" accept="image/*">
                <div class="mt-2">
                  <img id="preview_foto_pesanan" src="{{asset('thumbnail.png')}}" alt="preview foto pesanan" style="width: 120px; height: 120px; object-fit: cover;" class="rounded border">
                </div>
                <span class="invalid-feedback" role="alert" id="foto_pesananError">
                  <strong></strong>
                </span>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="row mt-3">
            <div class="col-lg-12" id="tab_detail">
              <div class="nav nav-pills">
                <a class="nav-item nav-link active" style="width: 50%;" data-bs-toggle="tab" href="#tab-pane-1"><i class="bx bx-shopping-bag"></i> Penjualan
                  <span class="error-tab text-danger">Error</span>
                </a>
                <a class="nav-item nav-link" style="width: 50%;" data-bs-toggle="tab" href="#tab-pane-2"><i class="bx bx-wallet"></i> Pembayaran
                  <span class="error-tab text-danger">Error</span>
                </a>
              </div>
              <!-- gambar -->
              <div class="tab-content">
                <!-- detail penjualan -->
                <div class="tab-pane show active" id="tab-pane-1">
                  <div class="table-responsive">
                    <input type="" id="id_penjualan_detail_del" name="id_penjualan_detail_del" hidden="">
                    <button type="button" id="new_penjualan_detail" class="btn btn-info text-white btn-sm mb-2 mt-3">
                      <i class="fa fa-plus"></i> Pilih dan Masukkan Item
                    </button>
                    <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>No. </th>
                          <th>Item</th>
                          <th>Stok</th>
                          <th>Harga</th>
                          <th>Jumlah</th>
                          <th>Subtotal</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="table_penjualan_detail">
                      </tbody>
                    </table>

                  </div>
                </div>
                <!-- detail penjualan -->
                <!-- pembayaran -->
                <div class="tab-pane" id="tab-pane-2">
                  <input type="" hidden="" id="id_metode_pembayaran_del" name="id_metode_pembayaran_del">
                  <button type="button" id="new_metode_pembayaran" class="btn btn-info btn-sm mb-3">
                    <i class="fa fa-plus"></i> Pilih dan Masukkan Pembayaran
                  </button>
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>No. </th>
                          <th>Metode Pembayaran</th>
                          <th>Detail</th>
                          <th>Nominal Dibayar</th>
                          <th>Tanggal Pembayaran</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="table_metode_pembayaran_detail">
                      </tbody>
                    </table>

                  </div>
                </div>
                <!-- end -->
                <h5 class="subtotal_view mt-4"></h5>
                <h5 class="subtotal_bayar_view mb-5"></h5>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
          <button type="button" class="btn close"> Kembali</button>
        </form>
        <!--  -->
        <div style="display:none;">
          <table id="sample_table_penjualan_detail">
            <tr id="">
              <td><span class="sn" style="vertical-align:middle;"></span></td>   
              <td>
                <input autocomplete="off" name="barang[0][id_penjualan_detail]" hidden="" id="barang_0_id_penjualan_detail" class="form-control form-control-sm id_penjualan_detail_input">
                <!-- <input autocomplete="off" name="barang[0][id_barang]" hidden="" id="barang_0_id_barang" class="form-control form-control-sm id_barang_input"> -->
                <select name="barang[0][id_barang]" style="width: 150px;" id="barang_0_id_barang" class="form-control form-control-sm id_barang_input">
                  @foreach($barang as $brg)
                  <option value="{{ $brg->id_barang }}" more_stok="{{ $brg->stok_sekarang }}" more_stok_label="{{ $brg->stok_label }}" more_id="{{$brg->id_barang}}" more_harga="{{ $brg->harga_jual }}" more_type="{{ $brg->item_type }}">
                  <!--{{ $brg->kode_barang }} --> {{ $brg->nama_barang }}
                  </option>
                  @endforeach
                </select>
                <span class="invalid-feedback id_barang_input_error" role="alert" id="barang_0_id_barangError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" readonly="" name="barang[0][stok_sekarang]" id="barang_0_stok_sekarang" class="stok_sekarang_input form-control">
                <span class="invalid-feedback stok_sekarang_input_error" role="alert" id="barang_0_stok_sekarangError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" readonly="" name="barang[0][harga_jual]" id="barang_0_harga_jual" class="harga_jual_input form-control">
                <span class="invalid-feedback harga_jual_input_error" role="alert" id="barang_0_harga_jualError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="number" min="1" name="barang[0][jml_penjualan]" id="barang_0_jml_penjualan" class="jml_penjualan_input form-control">
                <span class="invalid-feedback jml_penjualan_input_error" role="alert" id="barang_0_jml_penjualanError">
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
                  <button type="button" class="delete-pen-det btn btn-sm btn-danger" data-id="0"><i class="bx bx-x"></i></button>
                </center>
              </td>
            </tr>
          </table>
        </div>
        <!--  -->
        <!-- bayar -->
        <div style="display:none;">
          <table id="sample_table_metode_pembayaran">
            <tr id="row_pembayaran">
              <td data-label="No."><span class="sn" style="vertical-align:middle;"></span></td>   
              <td>
                <input autocomplete="off" name="penjualan[0][id_penjualan_pembayaran]" hidden="" id="penjualan_0_id_penjualan_pembayaran" class="form-control form-control-sm id_penjualan_pembayaran_input">
                <select name="penjualan[0][metode_pembayaran]" style="width: 100%;" id="penjualan_0_metode_pembayaran" class="form-control form-control-sm metode_pembayaran_input"></select>
                <span class="invalid-feedback metode_pembayaran_input_error" role="alert" id="penjualan_0_metode_pembayaranError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" readonly="" name="penjualan[0][metode_detail]" id="penjualan_0_metode_detail" class="metode_detail_input form-control">
                <span class="invalid-feedback metode_detail_input_error" role="alert" id="penjualan_0_metode_detailError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="text" name="penjualan[0][nominal_pembayaran]" id="penjualan_0_nominal_pembayaran" class="nominal_pembayaran_input form-control">
                <span class="invalid-feedback nominal_pembayaran_input_error" role="alert" id="penjualan_0_nominal_pembayaranError">
                  <strong></strong>
                </span>
              </td>
              <td>
                <input type="date" name="penjualan[0][tanggal_pembayaran]" id="penjualan_0_tanggal_pembayaran" class="tanggal_pembayaran_input form-control">
                <span class="invalid-feedback tanggal_pembayaran_input_error" role="alert" id="penjualan_0_tanggal_pembayaranError">
                  <strong></strong>
                </span>
              </td>
              <td data-label="Action">
                <center>
                  <button type="button" class="delete-record btn btn-sm btn-danger" data-id="0"><i class="bx bx-x"></i></button>
                </center>
              </td>
            </tr>
          </table>
        </div>
        <!-- end -->
      </div>
    </div>
  </div>
