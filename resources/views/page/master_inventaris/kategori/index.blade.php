  @extends('page/layout/app')

  @section('title','Data Kategori')

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
              <li class="breadcrumb-item active" aria-current="page">Kategori</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Kategori
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Kategori
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_kategori" style="width: 100%;">
              <thead>
                <tr>
                  <th>No. </th>
                  <th>Kategori</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}.</td>
                  <td>{{$dt->nama_kategori}}</td>
                  <td>
                    <a href="javascript:void(0)" more_id="{{$dt->id_kategori}}" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a>
                    <a href="javascript:void(0)" more_id="{{$dt->id_kategori}}" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_kategori" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
       <form method="post" id="kategoriForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Nama Kategori <span class="text-danger">*</span></label>
              <input type="" hidden="" id="id_kategori" name="id_kategori">
              <input type="text" class="form-control" autocomplete="off" id="nama_kategori" name="nama_kategori">
              <span class="invalid-feedback" role="alert" id="nama_kategoriError">
                <strong></strong>
              </span>
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
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#kategoriForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#kategoriForm input").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Kategori');
        $("#modal_form_kategori").modal('show');
        ajaxUrl = "{{route('save.kategori')}}";
      }, 300);
    });
  });
  $(function () {
    $('#kategoriForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = $(this).serializeArray();
      $(".invalid-feedback").children("strong").text("");
      $("#kategoriForm input").removeClass("is-invalid");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          $('#kategoriForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#kategoriForm")[0].reset();
            $('#modal_form_kategori').modal('hide');
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
          $('#kategoriForm').data('submitted', false);
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
  function get_edit(kategoriID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master_inventaris/kategori/get_edit')}}"+"/"+kategoriID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_kategori").val(value.id_kategori);
            $("#nama_kategori").val(value.nama_kategori);
          });
        }
      },
      error: function(response) {
        get_edit(kategoriID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var kategoriID = $(this).attr('more_id');
    $("#kategoriForm")[0].reset();
    $(".invalid-feedback").children("strong").text("");
    $("#kategoriForm input").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Kategori');
    $("#modal_form_kategori").modal('show');
    ajaxUrl = "{{route('update.kategori')}}";
    if (kategoriID) {
      get_edit(kategoriID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    kategoriID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Kategori akan dihapus secara Permanent!',
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
          url: "{{url('page/master_inventaris/kategori/destroy')}}"+"/"+kategoriID,
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