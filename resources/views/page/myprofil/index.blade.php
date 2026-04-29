  @extends('page/layout/app')

  @section('title','My Profil')

  @section('content')
  @foreach($data as $dt)
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
              <li class="breadcrumb-item"><a href="">User</a></li>
              <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <section class="section">
     <div class="card mb-4">
      <h5 class="card-header">Profil</h5>
      <form id="profilForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="d-flex align-items-start align-items-sm-center gap-4">
            @if($dt->foto != NULL)
            <img src="{{asset('foto_profil')}}/{{$dt->foto}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
            @else
            <img src="{{asset('thumbnail.png')}}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
            @endif
            <div class="button-wrapper">
              <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                <span class="d-none d-sm-block">Upload foto baru</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
                <input
                type="file"
                id="upload"
                class="account-file-input"
                hidden
                name="foto"
                accept="image/png, image/jpeg" />
              </label>
              <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                <i class="bx bx-reset d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
              </button>
            </div>
          </div>
        </div>
        <hr class="my-0" />
        <div class="card-body">
          <div class="row">
            <input type="" hidden="" value="{{$dt->foto}}" name="fotoLama">
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">Nama <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="name" name="name" value="{{$dt->name}}" autofocus />
              <span class="invalid-feedback" role="alert" id="nameError">
                <strong></strong>
              </span>
            </div>
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">Email <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="email" name="email" value="{{$dt->email}}" autofocus />
              <span class="invalid-feedback" role="alert" id="emailError">
                <strong></strong>
              </span>
            </div>
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">Password</label>
              <input class="form-control" type="text" id="password" name="password" autofocus />
              <span class="invalid-feedback" role="alert" id="passwordError">
                <strong></strong>
              </span>
            </div>
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">Telepon <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="telepon" name="telepon" value="{{$dt->telepon}}" autofocus />
              <span class="invalid-feedback" role="alert" id="teleponError">
                <strong></strong>
              </span>
            </div>
            <div class="mb-3 col-md-6">
              <label for="firstName" class="form-label">Alamat <span class="text-danger">*</span></label>
              <input class="form-control" type="text" id="alamat" name="alamat" value="{{$dt->alamat}}" autofocus />
              <span class="invalid-feedback" role="alert" id="alamatError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
@endforeach
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
<script src="{{asset('panel/assets/js/pages-account-settings-account.js')}}"></script>
<script type="text/javascript">
 $(function () {
  $('#profilForm').submit(function (e) {
    e.preventDefault();
    if ($(this).data('submitted') === true) {
      return;
    }
    $(this).data('submitted', true);
    let formData = new FormData(this);
    $(".invalid-feedback").children("strong").text("");
    $("#profilForm input").removeClass("is-invalid");
    $("#loading").show();
    $.ajax({
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      contentType: false,
      processData: false,
      url : "{{route('update_profil')}}",
      data: formData,
      success: function (response) {
        $('#profilForm').data('submitted', false);
        $("#loading").hide();
        if (response.status == 'true') {
          $("#profilForm")[0].reset();
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
        $('#profilForm').data('submitted', false);
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
</script>
@endsection