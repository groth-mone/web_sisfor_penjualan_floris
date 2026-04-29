<!DOCTYPE html>

<html
lang="en"
class="light-style layout-wide customizer-hide"
dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Adelia Florist | AUTH LOGIN</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('store.jpeg')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />

  <link rel="stylesheet" href="{{asset('panel/assets/vendor/fonts/boxicons.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/pages/page-auth.css')}}" />

  <!-- Helpers -->
  <script src="{{asset('panel/assets/vendor/js/helpers.js')}}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js')}} in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('panel/assets/js/config.js')}}"></script>
  </head>
  <!-- ====== HEAD: Styles ====== -->
  <style>
    /* Overlay full screen */
    #session-loader {
      position: fixed;
      inset: 0;
      display: flex;                 /* langsung flex supaya selalu center */
      align-items: center;
      justify-content: center;
      background: RGBA(var(--bs-primary-rgb), var(--bs-bg-opacity, 1)) !important;           /* background gelap */
      z-index: 99999;
      opacity: 1;
      visibility: visible;
    }

    /* Card loader */
    .loader-card {
      width: 90%;
      max-width: 320px;
      padding: 24px 20px;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
      text-align: center;
    }

    /* Spinner */
    .spinner {
      width: 52px;
      height: 52px;
      border: 4px solid rgba(0,0,0,.1);
      border-top-color: #1e90ff;
      border-radius: 50%;
      margin: 0 auto 14px;
      animation: spin 1s linear infinite;
    }

    /* Teks utama */
    .loader-title {
      font-size: 16px;
      color: #0e1726;
      margin: 0 0 6px 0;
      font-weight: 700;
    }

    /* Teks tagline */
    .loader-sub {
      font-size: 14px;
      color: #334155;
      margin: 0;
    }

    /* Animasi */
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    @keyframes fadeOut {
      to { opacity: 0; visibility: hidden; }
    }

    /* Saat disembunyikan, fade out halus */
    #session-loader.hide {
      animation: fadeOut .5s ease forwards;
    }
  </style>


  <body>
    <!-- Content -->
    <div id="session-loader" style="display:none;">
      <div class="loader-card">
        <div class="spinner"></div>
        <p class="loader-title">ADELIA FLORIST</p>
        <p class="loader-sub">Selamat Datang dan Selamat Beraktivitas</p>
      </div>
    </div>

    <!--  -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div
          class="bs-toast toast toast-placement-ex m-2"
          role="alert"
          aria-live="assertive"
          aria-atomic="true"
          data-bs-delay="2000">
          <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-medium" id="titleText"></div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body" id="messageText"></div>
        </div>
        <select class="form-select placement-dropdown" hidden="" id="selectPlacement">
          <option value="top-0 end-0">Top right</option>
        </select>
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bold" style="text-transform: uppercase;">
                  <img src="{{asset('store.jpeg')}}" class="img img-thumbnail" width="180">
                </span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Halo, selamat datang 👋</h4>
            <p class="mb-4">Silakan masuk ke akun Anda.</p>

            <form id="loginForm" class="mb-6">
              @csrf
              <div class="mb-6">
                <label for="email" class="col-form-label">Email</label>
                <input type="text" class="form-control" required="" id="email" name="email" placeholder="Masukkan Email ..." autofocus />
              </div>
              <div class="mb-6 form-password-toggle">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="col-form-label mb-0" for="password">Password</label>
                </div>
                <div class="input-group">
                  <input type="password" id="password" class="form-control" required name="password"
                  placeholder="••••••••••••" aria-describedby="password" />
                </div>
              </div>
              <div class="mb-6">
                <button class="btn btn-primary mt-5 w-100 submit_login" type="submit">
                  LOGIN
                </button>
              </div>
            </form>


          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->


  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js')}} -->

  <script src="{{asset('panel/assets/vendor/libs/jquery/jquery.js')}}"></script>
  <script src="{{asset('panel/assets/vendor/libs/popper/popper.js')}}"></script>
  <script src="{{asset('panel/assets/vendor/js/bootstrap.js')}}"></script>
  <script src="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('panel/assets/vendor/js/menu.js')}}"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{asset('panel/assets/js/main.js')}}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js')}}"></script>
</body>
<script type="text/javascript">
  function showToast(selectedType, titleText, messageText) {
    const toastPlacementExample = document.querySelector('.toast-placement-ex');
    $("#titleText").html(titleText);
    $("#messageText").html(messageText);
  // toastPlacementBtn = document.querySelector('#showToastPlacement');
  let selectedPlacement, toastPlacement;
  // Dispose toast when open another
  selectedPlacement = document.querySelector('#selectPlacement').value.split(' ');

  toastPlacementExample.classList.add(selectedType);
  DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
  toastPlacement = new bootstrap.Toast(toastPlacementExample);
  toastPlacement.show();
}
function showLoaderLogin(callbackUrl) {
  const overlay = document.getElementById('session-loader');
  overlay.style.display = 'flex';
  // kasih jeda sebentar biar loader sempet muncul
  setTimeout(() => {
    window.location.href = callbackUrl;
  }, 2000); // 1,2 detik
}
$(function () {
  $('#loginForm').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    $(".submit_login").html('LOGIN &nbsp; <div class="spinner-border spinner-border-sm" role="status"></div>');
    $(".submit_login").attr('disabled',true);
    $.ajax({
     method: "POST",
     headers: {
      Accept: "application/json"
    },
    contentType: false,
    processData: false,
    url: " {{route('request.login')}} ",
    data: formData,
    success: function(response) {
      $(".submit_login").attr('disabled',false);
      $(".submit_login").html('LOGIN');
      if(response.status == 'true') {
        showToast('bg-primary', response.title, response.message);
        setTimeout(function() {
          showLoaderLogin(response.url);
        }, 1000);
          // document.location.href = response.url;
        }
        if (response.status == 'false') {
          showToast('bg-danger', response.title, response.message);
        }
      },
      error: function(response) {
        $(".submit_login").attr('disabled',false);
        $(".submit_login").html('LOGIN');
        Swal.fire({
          icon: "error",
          type: "error",
          title: 'Error',
          text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
        });
      }
    });     
  }); 
}); 

// localStorage.clear();
// var email = document.getElementById('email');
// 
document.addEventListener('keydown', function(event) {
  if (event.ctrlKey && (event.key === 'u' || event.key === 'U')) {
    event.preventDefault();
  }
});
document.addEventListener('keydown', function(event) {
  if (event.ctrlKey && event.shiftKey && (event.key === 'i' || event.key === 'I')) {
    event.preventDefault();
  }
});
document.addEventListener("contextmenu",function(e) {
  e.preventDefault();
}, false)
</script>
</html>
