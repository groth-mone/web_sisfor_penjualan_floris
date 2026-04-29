@extends('page/layout/app')

@section('title','Data Produk')

@section('content')
<div class="loading" id="loading" style="display: none;">
  <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
  <h4>Loading</h4>
</div>
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last"></div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Master Inventaris</a></li>
            <li class="breadcrumb-item active" aria-current="page">Produk</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          Data Produk Florist
          <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new">
            <i class="bx bx-plus"></i> Tambah Produk
          </button>
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-striped" id="tabel_produk" style="width: 100%;">
            <thead>
              <tr>
                <th>No.</th>
                <th>Kode</th>
                <th>Produk</th>
                <th>Harga Jual</th>
                <th>Stok Jadi</th>
                <th>Total Bahan</th>
                <th>Gambar</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $dt)
              <tr>
                <td>{{$loop->index + 1}}.</td>
                <td>{{$dt->kode_produk}}</td>
                <td>{{$dt->nama_produk}}</td>
                <td>Rp. {{number_format($dt->harga_jual,0,",",".")}}</td>
                <td>{{$dt->stok_produk_jadi}}</td>
                <td>{{$dt->total_bahan}}</td>
                <td>
                  @if(!empty($dt->gambar))
                  <img src="{{asset('foto_produk/'.$dt->gambar)}}" alt="gambar produk" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                  @else
                  <span class="badge bg-label-secondary">Tidak ada</span>
                  @endif
                </td>
                <td>
                  <a href="javascript:void(0)" more_id="{{$dt->id_produk}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                  <a href="javascript:void(0)" more_id="{{$dt->id_produk}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_produk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="produkForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <input hidden id="id_produk" name="id_produk">
            <input hidden id="gambar_lama" name="gambar_lama">
            <div class="col-lg-5">
              <label class="col-form-label d-block">Gambar Produk</label>
              <div class="border rounded p-2 bg-light">
                <img
                  id="preview_gambar"
                  src="{{asset('thumbnail.png')}}"
                  alt="preview"
                  class="rounded border w-100"
                  style="height: 340px; object-fit: cover;"
                >
              </div>
              <div class="mt-3">
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                <small class="text-muted">Preview utama produk dibuat lebih besar supaya hasil foto lebih mudah dicek saat input atau edit.</small>
                <span class="invalid-feedback" role="alert" id="gambarError"><strong></strong></span>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="row">
                <div class="col-md-6 mb-2">
                  <label class="col-form-label">Kode Produk <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="kode_produk" name="kode_produk" value="{{$kodeProdukBaru}}" readonly>
                  <small class="text-muted">Kode otomatis mengikuti nomor produk terakhir.</small>
                  <span class="invalid-feedback" role="alert" id="kode_produkError"><strong></strong></span>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="col-form-label">Stok Produk Jadi <span class="text-danger">*</span></label>
                  <input type="number" min="0" class="form-control" id="stok_produk_jadi" name="stok_produk_jadi" value="0">
                  <span class="invalid-feedback" role="alert" id="stok_produk_jadiError"><strong></strong></span>
                </div>
                <div class="col-12 mb-2">
                  <label class="col-form-label">Nama Produk <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nama_produk" name="nama_produk">
                  <span class="invalid-feedback" role="alert" id="nama_produkError"><strong></strong></span>
                </div>
                <div class="col-12 mb-2">
                  <label class="col-form-label">Harga Jual <span class="text-danger">*</span></label>
                  <input type="text" class="form-control rupiah" id="harga_jual" name="harga_jual">
                  <span class="invalid-feedback" role="alert" id="harga_jualError"><strong></strong></span>
                </div>
                <div class="col-12 mb-2">
                  <label class="col-form-label">Deskripsi</label>
                  <textarea class="form-control" rows="5" id="deskripsi" name="deskripsi"></textarea>
                </div>
              </div>
            </div>
          </div>

          <hr>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Resep / Komposisi Bahan</h6>
            <button type="button" id="new_bahan_detail" class="btn btn-info btn-sm">
              <i class="fa fa-plus"></i> Tambah Bahan
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Bahan</th>
                  <th>Stok Saat Ini</th>
                  <th>Jumlah Pakai</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="table_bahan_detail"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal"><span>Tutup</span></button>
          <button class="btn btn-primary submit"><i class="bx bx-save"></i> <span>Simpan</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<div style="display:none;">
  <table id="sample_table_bahan_detail">
    <tr>
      <td><span class="sn"></span></td>
      <td>
        <select name="bahan[0][id_barang]" class="form-control form-control-sm id_barang_input" style="width: 100%;">
          <option value="">Pilih bahan</option>
          @foreach($bahan as $item)
          <option value="{{$item->id_barang}}" more_stok="{{$item->stok_sekarang}}">
            {{$item->kode_barang}} - {{$item->nama_barang}}
          </option>
          @endforeach
        </select>
        <span class="invalid-feedback id_barang_input_error" role="alert" id="bahan_0_id_barangError"><strong></strong></span>
      </td>
      <td>
        <input type="text" readonly class="form-control stok_bahan_input" name="bahan[0][stok_bahan]">
      </td>
      <td>
        <input type="number" min="0.01" step="0.01" class="form-control jumlah_pakai_input" name="bahan[0][jumlah_pakai]">
        <span class="invalid-feedback jumlah_pakai_input_error" role="alert" id="bahan_0_jumlah_pakaiError"><strong></strong></span>
      </td>
      <td>
        <button type="button" class="delete-bahan btn btn-sm btn-danger"><i class="bx bx-x"></i></button>
      </td>
    </tr>
  </table>
</div>
@endsection

@section('css')
<style type="text/css">
  .lds-spinner,
  .lds-spinner div,
  .lds-spinner div:after { box-sizing: border-box; }
  .lds-spinner { color: #000; display: inline-block; position: relative; width: 80px; height: 80px; }
  .lds-spinner div { transform-origin: 40px 40px; animation: lds-spinner 1.2s linear infinite; }
  .lds-spinner div:after { content: " "; display: block; position: absolute; top: 3.2px; left: 36.8px; width: 6.4px; height: 17.6px; border-radius: 20%; background: #000; }
  .lds-spinner div:nth-child(1) { transform: rotate(0deg); animation-delay: -1.1s; }
  .lds-spinner div:nth-child(2) { transform: rotate(30deg); animation-delay: -1s; }
  .lds-spinner div:nth-child(3) { transform: rotate(60deg); animation-delay: -0.9s; }
  .lds-spinner div:nth-child(4) { transform: rotate(90deg); animation-delay: -0.8s; }
  .lds-spinner div:nth-child(5) { transform: rotate(120deg); animation-delay: -0.7s; }
  .lds-spinner div:nth-child(6) { transform: rotate(150deg); animation-delay: -0.6s; }
  .lds-spinner div:nth-child(7) { transform: rotate(180deg); animation-delay: -0.5s; }
  .lds-spinner div:nth-child(8) { transform: rotate(210deg); animation-delay: -0.4s; }
  .lds-spinner div:nth-child(9) { transform: rotate(240deg); animation-delay: -0.3s; }
  .lds-spinner div:nth-child(10) { transform: rotate(270deg); animation-delay: -0.2s; }
  .lds-spinner div:nth-child(11) { transform: rotate(300deg); animation-delay: -0.1s; }
  .lds-spinner div:nth-child(12) { transform: rotate(330deg); animation-delay: 0s; }
  @keyframes lds-spinner { 0% { opacity: 1; } 100% { opacity: 0; } }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
  let ajaxUrl = "";
  let global_bahan_detail = 0;
  let nextKodeProduk = '{{ $kodeProdukBaru }}';

  function keyupRupiah(angka, prefix)
  {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split = number_string.split(','),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }

  function formatRupiah(value) {
    let stringValue = value.toString();
    let parts = stringValue.split(".");
    let wholePart = parts[0];
    let decimalPart = parts.length > 1 ? "." + parts[1] : "";
    let formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return "Rp. " + formattedWholePart + decimalPart;
  }

  $(function () {
    $('#tabel_produk').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true
    });
  });

  function addBahanRow(detail = null) {
    const content = $("#sample_table_bahan_detail tr").clone();
    const size = global_bahan_detail++;
    content.attr('id', 'bhn-' + size);
    content.find('.delete-bahan').attr('data-id', size);
    content.find('.id_barang_input').attr('id', 'bahan_' + size + '_id_barang').attr('name', 'bahan[' + size + '][id_barang]');
    content.find('.stok_bahan_input').attr('id', 'bahan_' + size + '_stok_bahan').attr('name', 'bahan[' + size + '][stok_bahan]');
    content.find('.jumlah_pakai_input').attr('id', 'bahan_' + size + '_jumlah_pakai').attr('name', 'bahan[' + size + '][jumlah_pakai]');
    content.find('.id_barang_input_error').attr('id', 'bahan_' + size + '_id_barangError');
    content.find('.jumlah_pakai_input_error').attr('id', 'bahan_' + size + '_jumlah_pakaiError');
    content.find('.id_barang_input').select2({
      dropdownParent: $("#modal_form_produk"),
      placeholder: "Pilih bahan"
    }).on('change', function() {
      const stok = $(this).find(':selected').attr('more_stok') || '';
      content.find('.stok_bahan_input').val(stok);
    });
    content.appendTo('#table_bahan_detail');

    if (detail) {
      content.find('.id_barang_input').val(detail.id_barang).trigger('change');
      content.find('.jumlah_pakai_input').val(detail.jumlah_pakai);
      if (detail.stok_bahan !== undefined && detail.stok_bahan !== null) {
        content.find('.stok_bahan_input').val(detail.stok_bahan);
      }
    } else {
      content.find('.id_barang_input').val(null).trigger('change');
    }

    $('#table_bahan_detail tr').each(function(index) {
      $(this).find('span.sn').html(index + 1);
    });
  }

  function resetFormProduk() {
    $('#produkForm')[0].reset();
    $('#id_produk').val('');
    $('#gambar_lama').val('');
    $('#kode_produk').val(nextKodeProduk).prop('readonly', true);
    $('#table_bahan_detail').html('');
    $('#preview_gambar').attr('src', "{{asset('thumbnail.png')}}");
    $('#produkForm input, #produkForm textarea, #produkForm select').removeClass('is-invalid');
    $('.invalid-feedback').children('strong').text('');
    global_bahan_detail = 0;
  }

  $(".new").click(function() {
    resetFormProduk();
    $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Produk');
    ajaxUrl = "{{route('save.produk')}}";
    $("#modal_form_produk").modal('show');
  });

  $(document).on('click', '#new_bahan_detail', function() {
    addBahanRow();
  });

  $(document).on('click', '.delete-bahan', function() {
    $('#bhn-' + $(this).attr('data-id')).remove();
    $('#table_bahan_detail tr').each(function(index) {
      $(this).find('span.sn').html(index + 1);
    });
  });

  $('#gambar').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      $('#preview_gambar').attr('src', URL.createObjectURL(file));
    }
  });

  $('.rupiah').on('input', function(e) {
    $(this).val(keyupRupiah(e.target.value, 'Rp. '));
  });

  $('#produkForm').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    $(".submit").attr('disabled', true);
    $.ajax({
      method: "POST",
      headers: { Accept: "application/json" },
      contentType: false,
      processData: false,
      url: ajaxUrl,
      data: formData,
      success: function(response) {
        $(".submit").attr('disabled', false);
        if (response.status == 'true') {
          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'OKE'
          }).then((result) => {
            if (result.isConfirmed) {
              document.location.href = '';
            }
          });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: response.message });
        }
      },
      error: function(response) {
        $(".submit").attr('disabled', false);
        if (response.status === 422) {
          let errors = response.responseJSON.errors;
          Object.keys(errors).forEach(function(key) {
            var keyTemp = key.replaceAll(".", "_");
            $("#" + keyTemp).addClass("is-invalid");
            $("#" + keyTemp + "Error").children("strong").text(errors[key][0]);
          });
        } else {
          Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
        }
      }
    });
  });

  $(document).on('click', '.edit', function() {
    resetFormProduk();
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Produk');
    ajaxUrl = "{{route('update.produk')}}";
    const produkID = $(this).attr('more_id');
    $.ajax({
      type: "GET",
      url: "{{url('page/master_inventaris/produk/get_edit')}}/" + produkID,
      success: function(response) {
        $('#id_produk').val(response.produk.id_produk);
        $('#kode_produk').val(response.produk.kode_produk).prop('readonly', false);
        $('#nama_produk').val(response.produk.nama_produk);
        $('#harga_jual').val(formatRupiah(response.produk.harga_jual, 'Rp. '));
        $('#stok_produk_jadi').val(response.produk.stok_produk_jadi);
        $('#deskripsi').val(response.produk.deskripsi);
        $('#gambar_lama').val(response.produk.gambar);
        if (response.produk.gambar) {
          $('#preview_gambar').attr('src', "{{asset('foto_produk')}}/" + response.produk.gambar);
        }
        $.each(response.detail, function(_, item) {
          addBahanRow(item);
        });
        $("#modal_form_produk").modal('show');
      }
    });
  });

  $(document).on('click', '.delete', function(event) {
    event.preventDefault();
    const produkID = $(this).attr('more_id');
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data produk akan dihapus secara permanent!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: "GET",
          url: "{{url('page/master_inventaris/produk/destroy')}}/" + produkID,
          success: function(response) {
            if (response.status == 'true') {
              Swal.fire({ title: 'Success', text: response.message, icon: 'success' }).then(() => document.location.href = '');
            } else {
              Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
          }
        });
      }
    });
  });
</script>
@endsection
