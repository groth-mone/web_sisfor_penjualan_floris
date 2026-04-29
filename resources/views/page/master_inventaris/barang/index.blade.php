  @extends('page/layout/app')

  @section('title','Data Bahan')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
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
              <li class="breadcrumb-item"><a href="">Master Inventaris</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bahan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Bahan
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Bahan
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="tabel_barang" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th>Kode</th>
                  <th>Kategori</th>
                  <th>Foto</th>
                  <th>Bahan</th>
                  <th>Satuan</th>
                  <th>Stok</th>
                  <th>Min.</th>
                  <th>Harga Default</th>
                  <th data-priority="2">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}.</td>
                  <td>{{$dt->kode_barang}}</td>
                  <td>{{$dt->nama_kategori}}</td>
                  <td>
                    @if(!empty($dt->gambar))
                    <img src="{{asset('foto_bahan/'.$dt->gambar)}}" alt="gambar bahan" style="width: 52px; height: 52px; object-fit: cover;" class="rounded">
                    @else
                    <span class="badge bg-label-secondary">Tidak ada</span>
                    @endif
                  </td>
                  <td>{{$dt->nama_barang}}</td>
                  <td>{{$dt->satuan_barang}}</td>
                  <td>
                    {{$dt->stok_sekarang}}
                    @if($dt->stok_sekarang <= ($dt->stok_minimum ?? 1) && $dt->stok_sekarang > 0)
                    <br>
                    <small class="text text-warning">Stok menipis</small>
                    @elseif($dt->stok_sekarang < 1)
                    <small class="text text-danger">Stok habis</small>
                    @endif
                  </td>
                  <td>{{$dt->stok_minimum ?? 0}}</td>
                  <td>Rp. {{number_format($dt->harga_default ?? 0,0,",",".")}}</td>
                  <td>
                    <a href="javascript:void(0)" more_id="{{$dt->id_barang}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                    <a href="javascript:void(0)" more_id="{{$dt->id_barang}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_barang" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1"></h5>
        <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
       <form method="post" id="barangForm" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
          <input type="" hidden="" id="id_barang" name="id_barang">
          <input type="hidden" id="gambar_lama" name="gambar_lama">
          <div class="col-lg-5">
            <label class="col-form-label d-block">Foto Bahan</label>
            <div class="border rounded p-2 bg-light">
              <img
                id="preview_gambar"
                src="{{asset('thumbnail.png')}}"
                alt="preview bahan"
                class="rounded border w-100"
                style="height: 320px; object-fit: cover;"
              >
            </div>
            <div class="mt-3">
              <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
              <small class="text-muted">Preview utama bahan ditampilkan besar agar foto mudah dicek saat input atau edit.</small>
              <span class="invalid-feedback" role="alert" id="gambarError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="col-form-label">Kategori <span class="text-danger">*</span></label>
                  <select class="form-control select_opsi" style="width: 100%;" id="id_kategori" name="id_kategori">
                    @foreach($kategori as $ktg)
                    <option value="{{$ktg->id_kategori}}">{{$ktg->nama_kategori}}</option>
                    @endforeach
                  </select>
                  <span class="invalid-feedback" role="alert" id="id_kategoriError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Kode Bahan <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" autocomplete="off" id="kode_barang" name="kode_barang" value="{{$kodeBarangBaru}}" readonly>
                  <small class="text-muted">Kode otomatis mengikuti nomor bahan terakhir.</small>
                  <span class="invalid-feedback" role="alert" id="kode_barangError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Satuan Bahan <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" autocomplete="off" id="satuan_barang" name="satuan_barang">
                  <span class="invalid-feedback" role="alert" id="satuan_barangError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="col-form-label">Nama Bahan <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" autocomplete="off" id="nama_barang" name="nama_barang">
                  <span class="invalid-feedback" role="alert" id="nama_barangError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Stok Minimum</label>
                  <input type="number" min="0" class="form-control" autocomplete="off" id="stok_minimum" name="stok_minimum" value="0">
                  <span class="invalid-feedback" role="alert" id="stok_minimumError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-form-label">Harga Default</label>
                  <input type="text" class="form-control harga_default_input" autocomplete="off" id="harga_default" name="harga_default">
                  <span class="invalid-feedback" role="alert" id="harga_defaultError">
                    <strong></strong>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">
          <span>Tutup</span>
        </button>
        <button class="btn btn-primary ml-1 submit">
          <i class="bx bx-save"></i> <span>Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>
</div>
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
    $('#tabel_barang').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true
    });
  });
  $("#id_kategori").select2({
    placeholder: ".: KATEGORI :.",
    dropdownParent: $("#modal_form_barang")
  });
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
  var ajaxUrl = "";
  var nextKodeBarang = '{{ $kodeBarangBaru }}';
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#barangForm")[0].reset();
        $("#id_kategori").val(null).trigger('change');
        $(".invalid-feedback").children("strong").text("");
        $("#barangForm input").removeClass("is-invalid");
        $("#barangForm select").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Bahan');
        $("#kode_barang").val(nextKodeBarang).prop('readonly', true);
        $("#preview_gambar").attr('src', "{{asset('thumbnail.png')}}");
        $("#gambar_lama").val('');
        $("#modal_form_barang").modal('show');
        ajaxUrl = "{{route('save.barang')}}";
      }, 300);
    });
  });
  $(document).on('input', '.harga_default_input', function(e) {
    $(this).val(keyupRupiah(e.target.value, 'Rp. '));
  });
  $('#gambar').on('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      $('#preview_gambar').attr('src', URL.createObjectURL(file));
    }
  });
  $(function () {
    $('#barangForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#barangForm input").removeClass("is-invalid");
      $("#barangForm select").removeClass("is-invalid");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          $('#barangForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#barangForm")[0].reset();
            $('#modal_form_barang').modal('hide');
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
          } else {
            Swal.fire({
              title: 'Error',
              text: response.message,
              icon: 'error',
              type: 'error'
            });
          }
        },
        error: function (response) {
          $('#barangForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: response.message,
              icon: 'error',
              type: 'error'
            });
          }
        }
      });
    });
  });
  function get_edit(barangID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master_inventaris/barang/get_edit')}}"+"/"+barangID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_barang").val(value.id_barang);
            $("#id_kategori").val(value.id_kategori).trigger('change');
            $("#kode_barang").val(value.kode_barang).prop('readonly', false);
            $("#nama_barang").val(value.nama_barang);
            $("#satuan_barang").val(value.satuan_barang);
            $("#stok_minimum").val(value.stok_minimum);
            $("#harga_default").val(formatRupiah(value.harga_default || 0));
            $("#gambar_lama").val(value.gambar);
            if (value.gambar) {
              $("#preview_gambar").attr('src', "{{asset('foto_bahan')}}/" + value.gambar);
            } else {
              $("#preview_gambar").attr('src', "{{asset('thumbnail.png')}}");
            }
          });
        }
      },
      error: function(response) {
        get_edit(barangID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var barangID = $(this).attr('more_id');
    $("#id_kategori").val(null).trigger('change');
    $("#barangForm")[0].reset();
    $(".invalid-feedback").children("strong").text("");
    $("#barangForm input").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Bahan');
    $("#kode_barang").prop('readonly', false);
    $("#modal_form_barang").modal('show');
    ajaxUrl = "{{route('update.barang')}}";
    if (barangID) {
      get_edit(barangID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    barangID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data bahan akan dihapus secara Permanent!',
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
          url: "{{url('page/master_inventaris/barang/destroy')}}"+"/"+barangID,
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
