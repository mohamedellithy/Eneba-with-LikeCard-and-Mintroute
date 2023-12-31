@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-6 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">مرحبا ! 🎉</h5>
                            <p class="mb-4">
                                قم بادارة سيستم ادارة بيع الاأكواد
                                <br/>
                                <br/>
                            </p>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">
                                ابدأ الان
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4 order-0">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card" style="background-color: #880e4f;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد المنتجات المربوطة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\Product::count() }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card" style="background-color: #004d40cc;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد الأكواد المستخدمة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\OfflineCode::where('status_used','used')->count() }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4 order-0">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #bf360cc4;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد الأكواد الغير مستخدمة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\OfflineCode::where('status_used','unused')->count() }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #6a1b9ae8;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد الاكواد من لايك كارد</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\OfflineCode::where('product_type','likecard')->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color:#558b2fbd;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد الاكواد من اينيبا</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\OfflineCode::where('product_type','eneba')->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #01579bdb;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد الطلبيات لايك كارد</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\ProviderOrder::where('provider_name','LikeCard')->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #00838fc9;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد المزادات</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\Auction::count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #9e9d24d4;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد المزادات النشطة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\Auction::where('status',1)->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color: #37474f;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد طلبيات اينيبا المكتملة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\EnebaOrder::where('status_order','PROVIDE')->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-3 mb-4">
                    <div class="card" style="background-color:#424242;color: white !important;">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">عدد طلبيات اينيبا الغير مكتملة</span>
                            <h3 class="card-title mb-2" style="color: white !important;">
                                {{ \App\Models\EnebaOrder::where('status_order','RESERVE')->count()  }}
                            </h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
