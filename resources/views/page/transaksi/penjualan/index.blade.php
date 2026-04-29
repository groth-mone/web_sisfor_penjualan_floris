  @extends('page/layout/app')

  @section('title','Data Penjualan')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading" id="pageIndex">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Transaksi</a></li>
              <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Penjualan
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Penjualan
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="tabel_pembelian" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="2">No.</th>
                  <th data-priority="3">Kode</th>
                  <th data-priority="4">Tanggal</th>
                  <th data-priority="5">Pelanggan</th>
                  <th data-priority="6">Status Kirim</th>
                  <th data-priority="7">Ongkir</th>
                  <th data-priority="8">Total Pesanan</th>
                  <th data-priority="9">Total Dibayar</th>
                  <th data-priority="10">Kekurangan</th>
                  <th data-priority="11">Keterangan</th>
                  <th data-priority="1">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}</td>
                  <td>{{$dt->kode_penjualan}}</td>
                  <td>{{$dt->tanggal_penjualan}}</td>
                  <td>{{$dt->pelanggan}}</td>
                  <td>{{$dt->status_pengiriman ?? '-'}}</td>
                  <td>Rp. {{ number_format($dt->ongkir ?? 0, 0, ",", ".") }}</td>
                  <td>Rp. {{ number_format($dt->total_penjualan, 0, ",", ".") }}</td>
                  <td>Rp. {{ number_format($dt->total_pembayaran, 0, ",", ".") }}</td>
                  <td>
                    @if($dt->total_pembayaran < $dt->total_penjualan)
                    Kekurangan: Rp. {{ number_format($dt->total_penjualan - $dt->total_pembayaran, 0, ",", ".") }}
                    @else
                    Lunas
                    @endif
                  </td>
                  <td>{{$dt->keterangan_penjualan}}</td>
                  <td>
                    <div class="d-flex align-items-center gap-1">
                      <a href="javascript:void(0)" more_id="{{$dt->id_penjualan}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                      @if($dt->total_pembayaran >= $dt->total_penjualan)
                      <a href="javascript:void(0)" more_id="{{$dt->id_penjualan}}" class="btn btn-info text-white rounded-pill btn-sm confirm"><i class="bx bx-check"></i></a>
                      @endif
                      <a href="javascript:void(0)" more_id="{{$dt->id_penjualan}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                        <div class="dropdown-menu">
                          <a href="{{route('invoice.penjualan',$dt->id_penjualan)}}" target="_blank" class="dropdown-item"><i class="bx bx-receipt"></i> Kwitansi</a>
                          <a href="https://wa.me/62{{substr($dt->nomor_pelanggan,1)}}" target="_blank" class="dropdown-item btn_modal"><i class="bx bxl-whatsapp"></i> Kirim Pesan</a>
                        </div>
                      </div>
                    </div>
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
  <!--  -->
  @include('page.transaksi.penjualan.form')
  <!--  -->
  @endsection
  @section('css')
  <style type="text/css">
    .lds-spinner,
    .lds-spinner div,
    .lds-spinner div:after {
      box-sizing: border-box;
    }
    .lds-spinner {
      color: #000;
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
    }
    .lds-spinner div {
      transform-origin: 40px 40px;
      animation: lds-spinner 1.2s linear infinite;
    }
    .lds-spinner div:after {
      content: " ";
      display: block;
      position: absolute;
      top: 3.2px;
      left: 36.8px;
      width: 6.4px;
      height: 17.6px;
      border-radius: 20%;
      background: #000;
    }
    .lds-spinner div:nth-child(1) {
      transform: rotate(0deg);
      animation-delay: -1.1s;
    }
    .lds-spinner div:nth-child(2) {
      transform: rotate(30deg);
      animation-delay: -1s;
    }
    .lds-spinner div:nth-child(3) {
      transform: rotate(60deg);
      animation-delay: -0.9s;
    }
    .lds-spinner div:nth-child(4) {
      transform: rotate(90deg);
      animation-delay: -0.8s;
    }
    .lds-spinner div:nth-child(5) {
      transform: rotate(120deg);
      animation-delay: -0.7s;
    }
    .lds-spinner div:nth-child(6) {
      transform: rotate(150deg);
      animation-delay: -0.6s;
    }
    .lds-spinner div:nth-child(7) {
      transform: rotate(180deg);
      animation-delay: -0.5s;
    }
    .lds-spinner div:nth-child(8) {
      transform: rotate(210deg);
      animation-delay: -0.4s;
    }
    .lds-spinner div:nth-child(9) {
      transform: rotate(240deg);
      animation-delay: -0.3s;
    }
    .lds-spinner div:nth-child(10) {
      transform: rotate(270deg);
      animation-delay: -0.2s;
    }
    .lds-spinner div:nth-child(11) {
      transform: rotate(300deg);
      animation-delay: -0.1s;
    }
    .lds-spinner div:nth-child(12) {
      transform: rotate(330deg);
      animation-delay: 0s;
    }
    @keyframes lds-spinner {
      0% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
  </style>
  @endsection
  @section('scripts')
  <script type="text/javascript">
   $(function () {
    $('#tabel_pembelian').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true
    });
  });
   $(function () {
    $('[data-toggle="popover"]').popover()
  });
   function tanggal_indonesia(dateString) {
    const bulan = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
    ];

    const tanggal = dateString.split('-');
    const hari = tanggal[2];
    const bulanIndex = parseInt(tanggal[1]) - 1;
    const tahun = tanggal[0];

    return `${hari} ${bulan[bulanIndex]} ${tahun}`;
  }
  function keyupRupiah(angka, prefix)
  {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split    = number_string.split(','),
    sisa     = split[0].length % 3,
    rupiah     = split[0].substr(0, sisa),
    ribuan     = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }
  $("#id_supplier").select2({
    placeholder: ".: SUPPLIER :."
  });
  $("#status_pengiriman").select2({
    placeholder: ".: STATUS PENGIRIMAN :."
  });
  $(".close").click(function() {
    $("#pageIndex").attr('hidden',false);
    $("#pageForm").attr('hidden',true);
  });
  var ajaxUrl = "";
  var typeAction = "";
  var moreHargaJual = "";
  var moreIdBarang = "";
  var moreStok = "";
  var moreStokLabel = "";
  function formatRupiah(value) {
    let stringValue = value.toString();
    let parts = stringValue.split(".");
    let wholePart = parts[0];
    let decimalPart = parts.length > 1 ? "." + parts[1] : "";
    let formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    let formattedValue = "Rp. " + formattedWholePart + decimalPart;
    return formattedValue;
  }
  $(document).on('input', '.ongkir_input', function(e) {
    $(this).val(keyupRupiah(e.target.value,'Rp. '));
  });
  $('#foto_pesanan').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      $('#preview_foto_pesanan').attr('src', URL.createObjectURL(file));
    }
  });
  $(".new").click(function() {
    $("#pageIndex").attr('hidden',true);
    $("#loading").show();
    setTimeout(function() {
      $("#penjualanForm")[0].reset();
      $(".modal-title").html('<i class="fa fa-plus"></i> Form Tambah Penjualan');
      $(".invalid-feedback").children("strong").text("");
      $(".select_opsi").val(null).trigger('change');
      $("#penjualanForm input").removeClass("is-invalid");
      $("#penjualanForm select").removeClass("is-invalid");
      jQuery('.id_barang_input').attr('readonly',false);    
      jQuery('.jml_penjualan_input').attr('readonly',false);
      $("#penjualanForm textarea").removeClass("is-invalid");
      $("#modal_form_penjualan").modal('show');
      jQuery('.rec').remove();
      jQuery('.pen').remove();
      $(".error-tab").html("");
      $("#pageForm").attr('hidden',false);
      $(".subtotal_view").html('');
      $(".subtotal_bayar_view").html('');
      $("#status_pengiriman").val('Pesanan Masuk').trigger('change');
      $("#foto_pesanan_lama").val('');
      $("#preview_foto_pesanan").attr('src', "{{asset('thumbnail.png')}}");
      global_id_pembayaran = 0;
      global_id_penjualan_detail = 0;
      ajaxUrl = " {{route('save.penjualan')}} ";
      $("#loading").hide();
    }, 300);
  });
  var opsi_pembayaran = [
  {
    id: 'Tunai',
    text: 'Tunai'
  },
  {
    id: 'Transfer',
    text: 'Transfer'
  }
  ];
  let global_id_pembayaran = 0;
  let totalSubtotalBayar = 0; // Menyimpan total subtotal
  $(document).on('click', '#new_metode_pembayaran', function () {
    var content = jQuery("#sample_table_metode_pembayaran"+" #row_pembayaran"),
    size = global_id_pembayaran++,
    element = null,
    element = content.clone();
    element.attr('id','rec-'+size);
    element.attr('class','rec');
    element.find('.delete-record').attr('data-id', size);

    element.find('.id_penjualan_pembayaran_input').attr('id', 'penjualan_' + size + '_id_penjualan_pembayaran');
    element.find('.id_penjualan_pembayaran_input').attr('name', 'penjualan[' + size + '][id_penjualan_pembayaran]');

    element.find('.metode_pembayaran_input').attr('id', 'penjualan_' + size + '_metode_pembayaran');
    element.find('.metode_pembayaran_input').attr('name', 'penjualan[' + size + '][metode_pembayaran]');
    element.find('.metode_pembayaran_input_error').attr('id', 'penjualan_' + size + '_metode_pembayaranError');
    element.find('.metode_pembayaran_input').select2({
      placeholder: "Metode Pembayaran ....",
      data: opsi_pembayaran
    }).on('change',function(e) {
      var value = e.target.value;
      if (value) {
        if (value == 'Transfer') {
          element.find('.metode_detail_input').val(null);
          element.find(".metode_detail_input").attr('readonly',false);
          element.find(".metode_detail_input").attr('placeholder','Shopeepay/TF BCA/DANA/GOPAY/DLL');
        }else{
          element.find('.metode_detail_input').val(null);
          element.find(".metode_detail_input").attr('readonly',true);
          element.find(".metode_detail_input").attr('placeholder','');
        }
      }
    });
    element.find('.metode_pembayaran_input').val(null).trigger('change');

    element.find(".metode_detail_input").attr('readonly',true);

    element.find('.metode_detail_input').attr('id', 'penjualan_' + size + '_metode_detail');
    element.find('.metode_detail_input').attr('name', 'penjualan[' + size + '][metode_detail]');
    element.find('.metode_detail_input_error').attr('id', 'penjualan_' + size + '_metode_detailError');
    // element.find('.metode_detail_input').select2({
    //   dropdownParent: $("#modal_form_penjualan"),
    //   placeholder: "Pilih Bank ....",
    //   data: opsi_bank
    // });
    // element.find('.metode_detail_input').val(null).trigger('change');

    element.find('.nominal_pembayaran_input').attr('id', 'penjualan_' + size + '_nominal_pembayaran');
    element.find('.nominal_pembayaran_input').attr('name', 'penjualan[' + size + '][nominal_pembayaran]');
    element.find('.nominal_pembayaran_input_error').attr('id', 'penjualan_' + size + '_nominal_pembayaranError');
    element.find('.nominal_pembayaran_input').on('input',function(e) {
      $(this).val(keyupRupiah(e.target.value,'Rp. '));
      updateTotalSubtotalBayar();
    });

    element.find('.tanggal_pembayaran_input').attr('id', 'penjualan_' + size + '_tanggal_pembayaran');
    element.find('.tanggal_pembayaran_input').attr('name', 'penjualan[' + size + '][tanggal_pembayaran]');
    element.find('.tanggal_pembayaran_input_error').attr('id', 'penjualan_' + size + '_tanggal_pembayaranError');

    element.appendTo('#table_metode_pembayaran_detail');
    $('#table_metode_pembayaran_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
  });
  function updateTotalSubtotalBayar() {
    totalSubtotalBayar = 0;
    $('#table_metode_pembayaran_detail tr.rec').each(function () {
      var existingTotalBayar = $(this).find('.nominal_pembayaran_input').val();
      var numericTotalBayar = parseInt(existingTotalBayar.replace(/\D/g, ''), 10);
      totalSubtotalBayar += numericTotalBayar;
      $(".subtotal_bayar_view").html('Total Pembayaran : '+formatRupiah(totalSubtotalBayar,'Rp. '));
    });
  }
  $(document).on('click', '.delete-record', function () {
    var id = jQuery(this).attr('data-id');
    var more_id = jQuery(this).attr('more_id');
    var currentValues = $("#id_metode_pembayaran_del").val();
    if (currentValues) {
      if (more_id) {
        $("#id_metode_pembayaran_del").val(currentValues + ',' + more_id);
      }
    } else {
      $("#id_metode_pembayaran_del").val(more_id);
    }
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#rec-' + id).remove();
    var row = $(this).closest('tr.rec');
    var deletedTotalBayar = row.find('.nominal_pembayaran_input').val();
    var numericDeletedTotalBayar = parseInt(deletedTotalBayar.replace(/\D/g, ''), 10);
    row.remove();
    updateTotalSubtotalBayar();
    $(".subtotal_bayar_view").html('Total Pembayaran : '+formatRupiah(totalSubtotal,'Rp. '));
    $('#table_metode_pembayaran_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
    return true;
  });
  // penjualan detail
  let global_id_penjualan_detail = 0;
  let totalSubtotal = 0; // Menyimpan total subtotal
  $(document).on('click', '#new_penjualan_detail', function () {
    var content = jQuery("#sample_table_penjualan_detail tr"),
    size = global_id_penjualan_detail++,
    element = null,
    element = content.clone();
    element.attr('id','pen-'+size);
    element.attr('class','pen');
    element.find('.delete-pen-det').attr('data-id', size);

    element.find('.id_penjualan_detail_input').attr('id', 'barang_' + size + '_id_penjualan_detail');
    element.find('.id_penjualan_detail_input').attr('name', 'barang[' + size + '][id_penjualan_detail]');

    element.find('.id_barang_input').attr('id', 'barang_' + size + '_id_barang');
    element.find('.id_barang_input').attr('name', 'barang[' + size + '][id_barang]');
    element.find('.id_barang_input_error').attr('id', 'barang_' + size + '_id_barangError');
    element.find('.id_barang_input').select2({
      placeholder: ":. PILIH ITEM .:"
    }).on('change',function(e) {
      if (e.target.value) {
        element.find('.jml_penjualan_input').val('');
        element.find('.total_harga_input').val('');
        var barangID = e.target.value;
        var selectedOption = $(this).find(':selected');
        if (selectedOption.length > 0) {
          moreStok = selectedOption.attr('more_stok');
          moreStokLabel = selectedOption.attr('more_stok_label');
          moreHargaJual = selectedOption.attr('more_harga');
          moreIdBarang = selectedOption.attr('more_id');
          element.find('.stok_sekarang_input').val(moreStokLabel || moreStok);
          element.find('.harga_jual_input').val(formatRupiah(moreHargaJual,'Rp. '));
        }
      }else{
        element.find('.total_harga_input').val('');
        element.find('.stok_sekarang_input').val('');
        element.find('.harga_jual_input').val('');
      }
    });
    element.find('.id_barang_input').val(null).trigger('change');

    // element.find('.id_barang_input').attr('id', 'barang_' + size + '_id_barang');
    // element.find('.id_barang_input').attr('name', 'barang[' + size + '][id_barang]');

    element.find('.harga_jual_input').attr('id', 'barang_' + size + '_harga_jual');
    element.find('.harga_jual_input').attr('name', 'barang[' + size + '][harga_jual]');
    element.find('.harga_jual_input_error').attr('id', 'barang_' + size + '_harga_jualError');

    element.find('.stok_sekarang_input').attr('id', 'barang_' + size + '_stok_sekarang');
    element.find('.stok_sekarang_input').attr('name', 'barang[' + size + '][stok_sekarang]');
    element.find('.stok_sekarang_input_error').attr('id', 'barang_' + size + '_stok_sekarangError');

    element.find('.jml_penjualan_input').attr('id', 'barang_' + size + '_jml_penjualan');
    element.find('.jml_penjualan_input').attr('name', 'barang[' + size + '][jml_penjualan]');
    element.find('.jml_penjualan_input_error').attr('id', 'barang_' + size + '_jml_penjualanError');
    element.find('.jml_penjualan_input').on('input',function(e) {
      const jmlPenjualan = parseInt(e.target.value || 0);
      const totalHarga = moreHargaJual * jmlPenjualan;
      element.find('.total_harga_input').val(formatRupiah(totalHarga, 'Rp. '));
      updateTotalSubtotal();
    });

    element.find('.total_harga_input').attr('id', 'barang_' + size + '_total_harga');
    element.find('.total_harga_input').attr('name', 'barang[' + size + '][total_harga]');
    element.find('.total_harga_input_error').attr('id', 'barang_' + size + '_total_hargaError');

    element.appendTo('#table_penjualan_detail');
    $('#table_penjualan_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
  });
  function updateTotalSubtotal() {
    totalSubtotal = 0;
    $('#table_penjualan_detail tr.pen').each(function () {
      var existingTotalHarga = $(this).find('.total_harga_input').val() || '0';
      var numericTotalHarga = parseInt(existingTotalHarga.replace(/\D/g, ''), 10) || 0;
      totalSubtotal += numericTotalHarga;
    });
    $(".subtotal_view").html('Total Penjualan : ' + formatRupiah(totalSubtotal, 'Rp. '));
  }
  $(document).on('click', '.delete-pen-det', function () {
    var id = jQuery(this).attr('data-id');
    var more_id = jQuery(this).attr('more_id');
    var currentValues = $("#id_penjualan_detail_del").val();
    if (currentValues) {
      if (more_id) {
        $("#id_penjualan_detail_del").val(currentValues + ',' + more_id);
      }
    } else {
      $("#id_penjualan_detail_del").val(more_id);
    }
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#pen-' + id).remove();
    var row = $(this).closest('tr.pen');
    var deletedTotalHarga = row.find('.total_harga_input').val();
    var numericDeletedTotalHarga = parseInt(deletedTotalHarga.replace(/\D/g, ''), 10);
    row.remove();
    updateTotalSubtotal();
    $(".subtotal_view").html('Total Penjualan : '+formatRupiah(totalSubtotal,'Rp. '));
    $('#table_penjualan_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
    return true;
  });
  
  // 
  $(function () {
    $('#penjualanForm').submit(function(e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $("#loading").show();
      $(".submit").attr('disabled',true);
      $(".invalid-feedback").children("strong").text("");
      $("#penjualanForm input").removeClass("is-invalid");
      $("#penjualanForm select").removeClass("is-invalid");
      $("#penjualanForm textarea").removeClass("is-invalid");
      $(".error-tab").html("");
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url: ajaxUrl,
        data: formData,
        success: function (response) {
          $(".submit").attr('disabled',false);
          $('#penjualanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#penjualanForm")[0].reset();
            Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              type: 'success',
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: 'OKE'
            }).then((result) => {
              if (result.isConfirmed) {
                document.location.href = '';
              }
            });
          } else if(response.status == 'warning') {
            Swal.fire({
              icon: 'warning',
              type: 'warning',
              title: response.title,
              text: response.message
            });
          }else{
            Swal.fire({
              icon: 'error',
              type: 'error',
              title: 'Permintaan Error.',
              text: response.message
            });
          }
        },
        error: function (response) {
          $(".submit").attr('disabled',false);
          $('#penjualanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              var key_temp = key.replaceAll(".", "_");
              $("#" + key_temp).addClass("is-invalid");
              $("#" + key_temp + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            Swal.fire({
              icon: 'error',
              type: 'error',
              title: 'Gagal',
              dangerMode: true,
              text: response.message
            });
          }
        }
      });
    });
  });
  // 
  function get_edit(penjualanID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/transaksi/penjualan/get_edit')}}"+"/"+penjualanID,
      success: function(response) {
        $("#pageForm").attr('hidden',false);
        $("#loading").hide();
        var totalSubtotal = 0;
        var totalSubtotalBayar = 0;
        global_id_penjualan_detail = 0;
        global_id_pembayaran = 0;
        $.each(response.data, function(key, value) {
          $("#id_penjualan").val(value.id_penjualan);
          $("#tanggal_penjualan").val(value.tanggal_penjualan);
          $("#pelanggan").val(value.pelanggan);
          $("#nomor_pelanggan").val(value.nomor_pelanggan);
          $("#keterangan_penjualan").val(value.keterangan_penjualan);
          $("#ongkir").val(formatRupiah(value.ongkir || 0));
          $("#alamat_pengiriman").val(value.alamat_pengiriman);
          $("#status_pengiriman").val(value.status_pengiriman || 'Pesanan Masuk').trigger('change');
          $("#catatan_kuitansi").val(value.catatan_kuitansi);
          $("#foto_pesanan_lama").val(value.foto_pesanan);
          if (value.foto_pesanan) {
            $("#preview_foto_pesanan").attr('src', "{{asset('foto_pesanan')}}/" + value.foto_pesanan);
          } else {
            $("#preview_foto_pesanan").attr('src', "{{asset('thumbnail.png')}}");
          }
        });
        $.each(response.pembayaran, function(key, value_pembayaran) {
          $("#new_metode_pembayaran").trigger('click');
        });
        setTimeout(function () {
          $('#table_metode_pembayaran_detail tr').each(function (index) {
            $(this).find('span.sn').html(index + 1);
            $(this).find('.delete-record').attr('more_id', response.pembayaran[index].id_penjualan_pembayaran);
            $(this).find('.id_penjualan_pembayaran_input').val(response.pembayaran[index].id_penjualan_pembayaran);
            $(this).find('.metode_pembayaran_input').val(response.pembayaran[index].metode_pembayaran).trigger('change');
            $(this).find('.metode_detail_input').val(response.pembayaran[index].metode_detail).trigger('change');
            $(this).find('.nominal_pembayaran_input').val(formatRupiah(response.pembayaran[index].nominal_pembayaran*1,'Rp. '));
            $(this).find('.tanggal_pembayaran_input').val(response.pembayaran[index].tanggal_pembayaran);
            var existingTotalBayar = $(this).find('.nominal_pembayaran_input').val();
            var numericTotalBayar = parseInt(existingTotalBayar.replace(/\D/g, ''), 10);
            totalSubtotalBayar += numericTotalBayar;
          }); 
          $(".subtotal_bayar_view").html('Total Pembayaran : '+formatRupiah(totalSubtotalBayar,'Rp. '));
        }, 500);
        $.each(response.detail, function(key, value_detail) {
          $("#new_penjualan_detail").trigger('click');
        });
        setTimeout(function () {
          $('#table_penjualan_detail tr').each(function (index) {
            $(this).find('span.sn').html(index + 1);
            $(this).find('.delete-pen-det').attr('more_id', response.detail[index].id_penjualan_detail);
            $(this).find('.id_penjualan_detail_input').val(response.detail[index].id_penjualan_detail);
            $(this).find('.id_barang_input').val(response.detail[index].katalog_id).trigger('change');
            $(this).find('.id_barang_input').attr('readonly',true);
            $(this).find('.jml_penjualan_input').val(response.detail[index].jml_penjualan);
            $(this).find('.jml_penjualan_input').attr('readonly',true);
            $(this).find('.total_harga_input').val(formatRupiah(response.detail[index].jml_penjualan*response.detail[index].harga_penjualan));
            var existingTotalHarga = $(this).find('.total_harga_input').val();
            var numericTotalHarga = parseInt(existingTotalHarga.replace(/\D/g, ''), 10);
            totalSubtotal += numericTotalHarga;
          });         
          $(".subtotal_view").html('Total Penjualan : '+formatRupiah(totalSubtotal,'Rp. '));
        }, 500);
      },
      error: function(response) {
        get_edit(penjualanID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var penjualanID = $(this).attr('more_id');
    $("#penjualanForm")[0].reset();
    $(".modal-title").html('<i class="fa fa-edit"></i> Form Ubah Penjualan');
    $(".select_opsi").val(null).trigger('change');
    $(".invalid-feedback").children("strong").text("");
    $("#penjualanForm input").removeClass("is-invalid");
    $("#penjualanForm select").removeClass("is-invalid");
    $("#penjualanForm textarea").removeClass("is-invalid");
    jQuery('.rec').remove();
    jQuery('.pen').remove();
    $(".error-tab").html("");
    $(".subtotal_view").html('');
    $(".subtotal_bayar_view").html('');
    $("#pageIndex").attr('hidden',true);
    ajaxUrl = " {{route('update.penjualan')}} ";
    global_id_pembayaran = 0;
    global_id_penjualan_detail = 0;
    if (penjualanID) {
      get_edit(penjualanID);
    }
  });
  // 
  $(document).on('click', '.delete', function (event) {
    penjualanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Penjualan akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "GET",
          url: "{{url('page/transaksi/penjualan/destroy')}}"+"/"+penjualanID,
          success: function(response) {
            if (response.status == 'true') {
             Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              type: 'success',
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: 'OKE'
            }).then((result) => {
              if (result.isConfirmed) {
                document.location.href = '';
              }
            });
          }else{
            Swal.fire({
              icon: 'error',
              type: 'error',
              title: 'Terjadi kesalahan',
              text: response.message
            });
          }
        },
        error: function(response) {
          Swal.fire({
            icon: 'error',
            type: 'error',
            title: 'Gagal',
            dangerMode: true,
            text: 'Terjadi kesalahan!'
          });
        }
      });
      }
    });
  });
  $(document).on('click', '.confirm', function (event) {
    penjualanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Konfirmasi Penjualan?',
      text: 'Data Penjualan diubah ke selesai.',
      icon: 'info',
      type: 'info',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "GET",
          url: "{{url('page/transaksi/penjualan/confirm')}}"+"/"+penjualanID,
          success: function(response) {
            if (response.status == 'true') {
             Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              type: 'success',
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: 'OKE'
            }).then((result) => {
              if (result.isConfirmed) {
                document.location.href = '';
              }
            });
          }else{
            Swal.fire({
              icon: 'error',
              type: 'error',
              title: 'Terjadi kesalahan',
              text: response.message
            });
          }
        },
        error: function(response) {
          Swal.fire({
            icon: 'error',
            type: 'error',
            title: 'Gagal',
            dangerMode: true,
            text: 'Terjadi kesalahan!'
          });
        }
      });
      }
    });
  });
</script>
@endsection
