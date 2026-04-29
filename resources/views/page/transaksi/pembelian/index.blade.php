  @extends('page/layout/app')

  @section('title','Data Pembelian')

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
              <li class="breadcrumb-item active" aria-current="page">Pembelian</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Pembelian
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Pembelian
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="tabel_pembelian" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th>Kode</th>
                  <th>Tanggal</th>
                  <th>Supplier</th>
                  <th>Jumlah Bahan</th>
                  <th>Total Pembelian</th>
                  <th>Keterangan</th>
                  <th data-priority="2">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}.</td>
                  <td>{{$dt->kode_pembelian}}</td>
                  <td>{{$dt->tanggal_pembelian}}</td>
                  <td>{{$dt->nama_supplier}}</td>
                  <td>{{$dt->jumlah_barang_dibeli}} bahan dibeli</td>
                  <td>Rp. {{number_format($dt->total_pembelian,0,",",".")}}</td>
                  <td>{{$dt->keterangan_pembelian}}</td>
                  <td>
                    <a href="javascript:void(0)" more_id="{{$dt->id_pembelian}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                    <a href="javascript:void(0)" more_id="{{$dt->id_pembelian}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
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
  @include('page.transaksi.pembelian.form')
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
  $(".close").click(function() {
    $("#pageIndex").attr('hidden',false);
    $("#pageForm").attr('hidden',true);
  })
  var ajaxUrl = "";
  var moreHargaBeli = "";
  var moreSatuan = "";
  function formatRupiah(value) {
    let stringValue = value.toString();
    let parts = stringValue.split(".");
    let wholePart = parts[0];
    let decimalPart = parts.length > 1 ? "." + parts[1] : "";
    let formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    let formattedValue = "Rp. " + formattedWholePart + decimalPart;
    return formattedValue;
  }
  $(".new").click(function() {
    $("#loading").show();
    $("#pageIndex").attr('hidden',true);
    setTimeout(function() {
      $("#loading").hide();
      $("#pembelianForm")[0].reset();
      $(".modal-title").html('<i class="fa fa-plus"></i> Form Tambah Pembelian');
      $(".invalid-feedback").children("strong").text("");
      $("#pembelianForm input").removeClass("is-invalid");
      $("#pembelianForm select").removeClass("is-invalid");
      $("#pembelianForm textarea").removeClass("is-invalid");
      $("#id_supplier").val(null).trigger('change');
      $("#pageForm").attr('hidden',false);
      jQuery('.pem').remove();
      $(".error-tab").html("");
      $(".subtotal_view").html('');
      global_id_pembelian_detail = 0;
      totalSubtotal = 0;
      ajaxUrl = " {{route('save.pembelian')}} ";
    }, 300);
  });
  // penjualan detail
  let global_id_pembelian_detail = 0;
  let totalSubtotal = 0; // Menyimpan total subtotal
  $(document).on('click', '#new_pembelian_detail', function () {
    var content = jQuery("#sample_table_pembelian_detail tr"),
    size = global_id_pembelian_detail++,
    element = null,
    element = content.clone();
    element.attr('id','pem-'+size);
    element.attr('class','pem');
    element.find('.delete-pem-det').attr('data-id', size);

    element.find('.id_pembelian_detail_input').attr('id', 'barang_' + size + '_id_pembelian_detail');
    element.find('.id_pembelian_detail_input').attr('name', 'barang[' + size + '][id_pembelian_detail]');

    element.find('.id_barang_input').attr('id', 'barang_' + size + '_id_barang');
    element.find('.id_barang_input').attr('name', 'barang[' + size + '][id_barang]');
    element.find('.id_barang_input_error').attr('id', 'barang_' + size + '_id_barangError');
    element.find('.id_barang_input').select2({
      placeholder: ":. PILIH BAHAN .:"
    }).on('change',function(e) {
      if (e.target.value) {
        element.find('.jml_pembelian_input').val('');
        element.find('.total_harga_input').val('');
        var barangID = e.target.value;
        var selectedOption = $(this).find(':selected');
        if (selectedOption.length > 0) {
          moreSatuan = selectedOption.attr('more_satuan');
          element.find('.satuan_barang_input').val(moreSatuan);
        }
      }else{
        element.find('.total_harga_input').val('');
        element.find('.satuan_barang_input').val('');
      }
    });
    element.find('.id_barang_input').val(null).trigger('change');

    element.find('.satuan_barang_input').attr('id', 'barang_' + size + '_satuan_barang');
    element.find('.satuan_barang_input').attr('name', 'barang[' + size + '][satuan_barang]');
    element.find('.satuan_barang_input_error').attr('id', 'barang_' + size + '_satuan_barangError');

    element.find('.harga_beli_input').attr('id', 'barang_' + size + '_harga_beli');
    element.find('.harga_beli_input').attr('name', 'barang[' + size + '][harga_beli]');
    element.find('.harga_beli_input_error').attr('id', 'barang_' + size + '_harga_beliError');
    element.find('.harga_beli_input').on('input',function(e) {
      $(this).val(keyupRupiah(e.target.value,'Rp. '));
      moreHargaBeli = e.target.value.replace(/[^0-9]/g, '');
    });

    element.find('.harga_jual_input').attr('id', 'barang_' + size + '_harga_jual');
    element.find('.harga_jual_input').attr('name', 'barang[' + size + '][harga_jual]');
    element.find('.harga_jual_input_error').attr('id', 'barang_' + size + '_harga_jualError');
    element.find('.harga_jual_input').on('input',function(e) {
      $(this).val(keyupRupiah(e.target.value,'Rp. '));
    });

    element.find('.jml_pembelian_input').attr('id', 'barang_' + size + '_jml_pembelian');
    element.find('.jml_pembelian_input').attr('name', 'barang[' + size + '][jml_pembelian]');
    element.find('.jml_pembelian_input_error').attr('id', 'barang_' + size + '_jml_pembelianError');
    element.find('.jml_pembelian_input').on('input',function(e) {
      const jmlPembelian = parseInt(e.target.value || 0);
      const totalHarga = moreHargaBeli * jmlPembelian;
      element.find('.total_harga_input').val(formatRupiah(totalHarga, 'Rp. '));
      updateTotalSubtotal();
    });

    element.find('.total_harga_input').attr('id', 'barang_' + size + '_total_harga');
    element.find('.total_harga_input').attr('name', 'barang[' + size + '][total_harga]');
    element.find('.total_harga_input_error').attr('id', 'barang_' + size + '_total_hargaError');

    element.appendTo('#table_pembelian_detail');
    $('#table_pembelian_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
  });
  function updateTotalSubtotal() {
    totalSubtotal = 0;
    $('#table_pembelian_detail tr.pem').each(function () {
      var existingTotalHarga = $(this).find('.total_harga_input').val() || '0';
      var numericTotalHarga = parseInt(existingTotalHarga.replace(/\D/g, ''), 10) || 0;
      totalSubtotal += numericTotalHarga;
    });
    $(".subtotal_view").html('Total Pembelian : ' + formatRupiah(totalSubtotal, 'Rp. '));
  }
  $(document).on('click', '.delete-pem-det', function () {
    var id = jQuery(this).attr('data-id');
    var more_id = jQuery(this).attr('more_id');
    var currentValues = $("#id_pembelian_detail_del").val();
    if (currentValues) {
      if (more_id) {
        $("#id_pembelian_detail_del").val(currentValues + ',' + more_id);
      }
    } else {
      $("#id_pembelian_detail_del").val(more_id);
    }
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#pem-' + id).remove();
    var row = $(this).closest('tr.pem');
    var deletedTotalHarga = row.find('.total_harga_input').val();
    var numericDeletedTotalHarga = parseInt(deletedTotalHarga.replace(/\D/g, ''), 10);
    row.remove();
    updateTotalSubtotal();
    $(".subtotal_view").html('Total Pembelian : '+formatRupiah(totalSubtotal,'Rp. '));
    $('#table_pembelian_detail tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
    return true;
  });
  
  // 
  $(function () {
    $('#pembelianForm').submit(function(e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $("#loading").show();
      $(".submit").attr('disabled',true);
      $(".invalid-feedback").children("strong").text("");
      $("#pembelianForm input").removeClass("is-invalid");
      $("#pembelianForm select").removeClass("is-invalid");
      $("#pembelianForm textarea").removeClass("is-invalid");
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
          $('#pembelianForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#pembelianForm")[0].reset();
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
          $('#pembelianForm').data('submitted', false);
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
  function get_edit(pembelianID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/transaksi/pembelian/get_edit')}}"+"/"+pembelianID,
      success: function(response) {
        $("#pageForm").attr('hidden',false);
        $("#loading").hide();
        var totalSubtotal = 0;
        var global_id_pembelian_detail = 0;
        $.each(response.data, function(key, value) {
          $("#id_pembelian").val(value.id_pembelian);
          $("#tanggal_pembelian").val(value.tanggal_pembelian);
          $("#id_supplier").val(value.id_supplier).trigger('change');
          $("#keterangan_pembelian").val(value.keterangan_pembelian);
        });
        $.each(response.pembelian, function(key, value_pembelian) {
          $("#new_pembelian_detail").trigger('click');
        });
        setTimeout(function () {
          $('#table_pembelian_detail tr').each(function (index) {
            $(this).find('span.sn').html(index + 1);
            $(this).find('.delete-pem-det').attr('more_id', response.pembelian[index].id_pembelian_detail);
            $(this).find('.id_pembelian_detail_input').val(response.pembelian[index].id_pembelian_detail);
            $(this).find('.id_barang_input').val(response.pembelian[index].id_barang).trigger('change');
            moreHargaBeli = response.pembelian[index]?.harga_beli;
            var hargaBeli = response.pembelian[index]?.harga_beli;
            var hargaJual = response.pembelian[index]?.harga_jual;
            if (hargaBeli !== undefined && hargaBeli !== null) {
              $(this).find('.harga_beli_input').val(formatRupiah(hargaBeli.toString(), 'Rp. '));
            }
            if (hargaJual !== undefined && hargaJual !== null) {
              $(this).find('.harga_jual_input').val(formatRupiah(hargaJual.toString(), 'Rp. '));
            }
            $(this).find('.jml_pembelian_input').val(response.pembelian[index].jml_pembelian);
            $(this).find('.total_harga_input').val(formatRupiah(response.pembelian[index].harga_beli*response.pembelian[index].jml_pembelian));
            var existingTotalBayar = $(this).find('.total_harga_input').val();
            var numericTotalBayar = parseInt(existingTotalBayar.replace(/\D/g, ''), 10);
            totalSubtotal += numericTotalBayar;
          }); 
          $(".subtotal_view").html('Total Pembelian : '+formatRupiah(totalSubtotal,'Rp. '));
        }, 500);
      },
      error: function(response) {
        get_edit(pembelianID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var pembelianID = $(this).attr('more_id');
    $("#pembelianForm")[0].reset();
    $(".modal-title").html('<i class="fa fa-edit"></i> Form Ubah Pembelian');
    $("#id_supplier").val(null).trigger('change');
    $(".invalid-feedback").children("strong").text("");
    $("#pembelianForm input").removeClass("is-invalid");
    $("#pembelianForm select").removeClass("is-invalid");
    $("#pembelianForm textarea").removeClass("is-invalid");
    jQuery('.pem').remove();
    $(".error-tab").html("");
    $(".subtotal_view").html('');
    $("#pageIndex").attr('hidden',true);
    ajaxUrl = " {{route('update.pembelian')}} ";
    if (pembelianID) {
      get_edit(pembelianID);
    }
  });
  // 
  $(document).on('click', '.delete', function (event) {
    pembelianID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Pembelian akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#loading").show();
        $.ajax({
          method: "GET",
          url: "{{url('page/transaksi/pembelian/destroy')}}"+"/"+pembelianID,
          success:function(response)
          {
            $("#loading").hide();
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
                title: 'Error',
                text: response.message,
                icon: 'error',
                type: 'error'
              });
            }
          },
          error: function(response) {
            $("#loading").hide();
            Swal.fire({
              title: 'Error',
              text: response.message,
              icon: 'error',
              type: 'error'
            });
          }
        })
      }
    });
  });
</script>
@endsection
