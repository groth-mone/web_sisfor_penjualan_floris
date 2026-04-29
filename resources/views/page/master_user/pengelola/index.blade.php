  @extends('page/layout/app')

  @section('title','Data User Pengelola')

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
              <li class="breadcrumb-item"><a href="">Master User</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pengelola</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data User Pengelola
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah User
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_kategori" style="width: 100%;">
             <thead>
              <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($data as $dt)
              <tr>
                <td>{{$loop->index+1}}.</td>
                <td>{{$dt->name}}</td>
                <td>{{$dt->email}}</td>
                <td>{{$dt->telepon}}</td>
                <td>
                  @if($dt->foto == NULL)
                  <span class="badge bg-primary text-white">Belum ada foto</span>
                  @else
                  <img src="{{asset('foto')}}/{{$dt->foto}}" class="rounded-circle" height="45" width="45">
                  @endif
                </td>
                <td>
                  @if($dt->status == 'Active')
                  <span class="badge bg-success text-white">Active</span>
                  @else
                  <span class="badge bg-danger text-white">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="javascript:void(0)" more_id="{{$dt->id}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                  <a href="javascript:void(0)" more_id="{{$dt->id}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
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
<div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_user" tabindex="-1" role="dialog"
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
      <form method="POST" id="userForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" autocomplete="off" class="form-control" id="name" name="name">
              <span class="invalid-feedback" role="alert" id="nameError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Email <span class="text-danger">*</span></label>
              <input type="text" autocomplete="off" class="form-control" id="email" name="email">
              <span class="invalid-feedback" role="alert" id="emailError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Telepon <span class="text-danger">*</span></label>
              <input type="number" autocomplete="off" class="form-control" id="telepon" name="telepon">
              <span class="invalid-feedback" role="alert" id="teleponError">
                <strong></strong>
              </span>
              <input type="hidden" id="id" name="id">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Password <span class="text-danger" id="password_label"></span></label>
              <input type="text" autocomplete="off" class="form-control" name="password" id="password">
              <span class="invalid-feedback" role="alert" id="passwordError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Status <span class="text-danger">*</span></label>
              <select class="form-control select_opsi" style="width: 100%;" id="status" name="status">
              </select>
              <span class="invalid-feedback" role="alert" id="statusError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label class="col-form-label">Alamat <span class="text-danger">*</span></label>
              <input type="text" autocomplete="off" class="form-control" name="alamat" id="alamat">
              <span class="invalid-feedback" role="alert" id="alamatError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Foto <span class="text-danger" id="foto_label"></span></label>
              <input type="text" hidden="" id="fotoLama" name="fotoLama">
              <input class="form-control" name="foto" type='file' accept="image/*" />
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
    $('#table_kategori').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true
    });
  });
  var ajaxUrl = "";
  var data_status = [
  {
    id: 'Active',
    text: 'Active'
  },
  {
    id: 'Inactive',
    text: 'Inactive'
  }
  ];
  $("#status").select2({
    dropdownParent: $("#modal_form_user"),
    placeholder: "Pilih Status ....",
    data: data_status
  });
  $(".new").click(function() {
    $("#userForm")[0].reset();
    $(".modal-title").html('<i class="fa fa-plus"></i> Form Tambah User Pengelola');
    $(".invalid-feedback").children("strong").text("");
    $("#userForm input").removeClass("is-invalid");
    $("#userForm select").removeClass("is-invalid");
    $(".select_opsi").val(null).trigger('change');
    $("#password_label").html('*');
    $("#foto_label").html('');
    $("#modal_form_user").modal('show');
    ajaxUrl = " {{route('save.pengelola')}} ";
  });
  $(function () {
    $('#userForm').submit(function(e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $("#loading").show();
      $(".submit").attr('disabled',true);
      $(".invalid-feedback").children("strong").text("");
      $("#userForm input").removeClass("is-invalid");
      $("#userForm select").removeClass("is-invalid");
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
          $('#userForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#userForm")[0].reset();
            $('#modal_form_user').modal('hide');
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
              icon: 'error',
              type: 'error',
              title: 'Gagal',
              dangerMode: true,
              text: response.message
            });
          }
        },
        error: function (response) {
          $(".submit").attr('disabled',false);
          $('#userForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            swal({
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
  function get_edit(userID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master_user/pengelola/get_edit')}}"+"/"+userID,
      success: function(response) {
        $("#loading").hide();
        $.each(response, function(key, value) {
          $("#id").val(value.id);
          $("#name").val(value.name);
          $("#email").val(value.email);
          $("#telepon").val(value.telepon);
          $("#fotoLama").val(value.foto);
          $("#alamat").val(value.alamat);
          $("#status").val(value.status).trigger('change');
        });
      },
      error: function(response) {
        get_edit(userID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var userID = $(this).attr('more_id');
    $("#userForm")[0].reset();
    $(".modal-title").html('<i class="fa fa-edit"></i> Form Ubah User Pengelola');
    $(".invalid-feedback").children("strong").text("");
    $("#userForm input").removeClass("is-invalid");
    $("#userForm select").removeClass("is-invalid");
    $("#password_label").html('');
    $("#foto_label").html('');
    $("#modal_form_user").modal('show');
    ajaxUrl = " {{route('update.pengelola')}} ";
    if (userID) {
      get_edit(userID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    userID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data User akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "GET",
          url: "{{url('page/master_user/pengelola/destroy')}}"+"/"+userID,
          success: function(response) {
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