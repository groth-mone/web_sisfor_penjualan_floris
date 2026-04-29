  @extends('page/layout/app')

  @section('title','Dashboard')

  @section('content')
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 mt-2">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <i class="bx bx-category" style="font-size: 0.5in;"></i>
              </div>
              <div class="dropdown">
                <button
                class="btn p-0"
                type="button"
                id="cardOpt3"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                <a class="dropdown-item" href=" {{route('index.kategori')}} ">Lihat Detail</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Kategori</span>
          <h3 class="card-title mb-2">{{$kategori}}</h3>
        </div>
      </div>
    </div>
    <div class="col-lg-4 mt-2">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-box" style="font-size: 0.5in;"></i>
            </div>
            <div class="dropdown">
              <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              >
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
              <a class="dropdown-item" href=" {{route('index.barang')}} ">Lihat Detail</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Bahan</span>
        <h3 class="card-title mb-2">{{count($barang)}}</h3>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <i class="bx bx-briefcase" style="font-size: 0.5in;"></i>
          </div>
          <div class="dropdown">
            <button
            class="btn p-0"
            type="button"
            id="cardOpt3"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            >
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
            <a class="dropdown-item" href=" {{route('index.supplier')}} ">Lihat Detail</a>
          </div>
        </div>
      </div>
      <span class="fw-semibold d-block mb-1">Supplier</span>
      <h3 class="card-title mb-2">{{$supplier}}</h3>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@section('scripts')
@endsection
