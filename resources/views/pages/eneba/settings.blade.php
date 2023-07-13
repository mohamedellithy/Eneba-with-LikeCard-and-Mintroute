@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Enebe</h5>
                            <div class="divider">
                                <div class="divider-text">Sandbox Credential</div>
                            </div>
                            <div class="row">
                                <form action="{{ route('application.eneba.update',['section' => 'sandbox']) }}" method="Post">
                                    @method('PUT')
                                    @csrf
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Auth ID</label>
                                                <input class="form-control" name="sandbox_auth_id" value="{{ isset($settings['sandbox_auth_id']) ? $settings['sandbox_auth_id'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Auth Secret</label>
                                                <input class="form-control" name="sandbox_auth_secret" value="{{ isset($settings['sandbox_auth_secret']) ? $settings['sandbox_auth_secret'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-1 btn-generate">
                                            <br/>
                                            <button type="submit" class="btn rounded-pill btn-primary">set</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="divider">
                                <div class="divider-text">Production Credential</div>
                            </div>
                            <div class="row">
                                <form action="{{ route('application.eneba.update',['section' => 'prod']) }}" method="Post">
                                    @method('PUT')
                                    @csrf
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Auth ID</label>
                                                <input class="form-control"  name="prod_auth_id" value="{{ isset($settings['prod_auth_id']) ? $settings['prod_auth_id'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Auth Secret</label>
                                                <input class="form-control"   name="prod_auth_secret" value="{{ isset($settings['prod_auth_secret']) ? $settings['prod_auth_secret'] : '' }}"  input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-1 btn-generate">
                                            <br/>
                                            <button type="submit" class="btn rounded-pill btn-primary">set</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="divider">
                                <div class="divider-text">Access Token</div>
                            </div>
                            <div class="row">
                                <form action="{{ route('application.eneba.regenrate_token') }}" method="Post">
                                    @method('PUT')
                                    @csrf
                                    <div class="row container-fields">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="">Access Token</label>
                                                <input class="form-control"  input="text" name="access_token" value="{{ isset($settings['access_token']) ? $settings['access_token'] : '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 btn-generate">
                                            <br/>
                                            <button type="submit" class="btn rounded-pill btn-primary">Regenerate</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View
                                            More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Profit</span>
                            <h3 class="card-title mb-2">$12,628</h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View
                                            More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <span>Sales</span>
                            <h3 class="card-title text-nowrap mb-1">$4,679</h3>
                            <small class="text-success fw-semibold"><i
                                    class="bx bx-up-arrow-alt"></i> +28.42%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom_style')
<style>
    .container-fields{
        padding: 1% 5% !important;
    }
    .container-fields label{
        line-height: 2.5em;
    }
    .btn-generate{
        padding: 11px;
    }
</style>
@endsection
