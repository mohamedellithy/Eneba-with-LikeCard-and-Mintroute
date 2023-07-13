@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <h5 class="card-title text-primary">LikeCard</h5>
                            <div class="divider">
                                <div class="divider-text">Production Credential</div>
                            </div>
                            <div class="row">
                                <form action="{{ route('application.likecard.update',['section' => 'prod']) }}" method="Post">
                                    @method('PUT')
                                    @csrf
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input class="form-control"  name="prod_email" value="{{ isset($settings['prod_email']) ? $settings['prod_email'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Device ID</label>
                                                <input class="form-control"   name="prod_deviceId" value="{{ isset($settings['prod_deviceId']) ? $settings['prod_deviceId'] : '' }}"  input="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Security Code</label>
                                                <input class="form-control"  name="prod_securityCode" value="{{ isset($settings['prod_securityCode']) ? $settings['prod_securityCode'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Password</label>
                                                <input class="form-control"   name="prod_password" value="{{ isset($settings['prod_password']) ? $settings['prod_password'] : '' }}"  input="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Phone</label>
                                                <input class="form-control"  name="prod_phone" value="{{ isset($settings['prod_phone']) ? $settings['prod_phone'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">hash Key</label>
                                                <input class="form-control"   name="prod_hash_key" value="{{ isset($settings['prod_hash_key']) ? $settings['prod_hash_key'] : '' }}"  input="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row container-fields">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Secret Key</label>
                                                <input class="form-control"  name="prod_secret_key" value="{{ isset($settings['prod_secret_key']) ? $settings['prod_secret_key'] : '' }}" input="text"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Secret Iv</label>
                                                <input class="form-control"   name="prod_secret_iv" value="{{ isset($settings['prod_secret_iv']) ? $settings['prod_secret_iv'] : '' }}"  input="text"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row container-fields">
                                        <div class="col-md-12 btn-generate">
                                            <br/>
                                            <button type="submit" class="btn rounded-pill btn-primary">set</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                    <img src="{{ asset('/assets/img/icons/unicons/wallet-info.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Balance</span>
                            <h3 class="card-title mb-2">
                                @isset($balance) @isset($balance['balance']) {{ $balance['balance'] }}  @endisset @endisset
                            </h3>
                            <small class="text-success fw-semibold">
                                <i class="bx bx-up-arrow-alt"></i> 
                                @isset($balance) @isset($balance['currency']) {{ $balance['currency'] }}  @endisset @endisset
                            </small>
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
        text-align: right;
    }
</style>
@endsection
