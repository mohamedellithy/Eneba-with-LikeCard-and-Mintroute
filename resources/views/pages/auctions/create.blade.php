@extends('master')
@php
$eneba_id   = request('eneba_id');
$prices     = GetAuctionPrices($eneba_id);
$low_price  = $prices->min('amount');
$high_price = $prices->max('amount');
@endphp
@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"></h5>
                <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body">
                <form class="inner-auction-info">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" checked="">
                        <label class="form-check-label" for="flexSwitchCheckChecked">تشغيل المزاد</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">السعر من لايك كارد</label>
                        <input type="text" class="form-control" id="basic-default-fullname" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-company">الحد الادني للتسعير</label>
                        <input type="text" class="form-control" value="{{ FormatePrice($low_price,false) }}" id="basic-default-company" placeholder="ACME Inc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-email">الحد الأقصي للتسعير</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="basic-default-email"  value="{{ FormatePrice($high_price,false) }}" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="automation" id="flexSwitchCheckChecked" checked="">
                        <label class="form-check-label" for="flexSwitchCheckChecked">تغير السعر بشكل اتوماتك</label>
                    </div>
                    <div class="mb-3">
                        <label for="defaultSelect" class="form-label">مدة مراجعة السعر</label>
                        <select id="defaultSelect" name="change_time" class="form-select">
                            <option>بشكل اتوماتك</option>
                            <option value="5">5 دقائق</option>
                            <option value="10">10 دقائق</option>
                            <option value="15">15 دقائق</option>
                            <option value="60">ساعة</option>
                            <option value="120">ساعتين</option>
                            <option value="180">3 ساعات</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-email">قيمة التغير فى السعر</label>
                        <div class="input-group input-group-merge">
                            <input type="text" name="price_step" id="basic-default-email"  value="0.01" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        انشاء المزاد
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <small class="text-muted float-end">تفاصيل المنتج</small>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">اسم المنتج</label>
                    <div class="input-group input-group-merge">
                        <h5>
                            {{ $product_eneba['S_product']['name'] }}
                        </h5>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">أعلي سعر مزاد</label>
                    <div class="input-group input-group-merge">
                        <h5>
                            {{ $high_price ? FormatePrice($high_price) : 0 }}
                        </h5>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">أقل سعر مزاد</label>
                    <div class="input-group input-group-merge">
                        <h5>
                            {{ $low_price ? FormatePrice($low_price) : 0  }}
                        </h5>
                    </div>
                </div>

                <a target="_blank" href="{{ 'https://www.eneba.com/'.$product_eneba['S_product']['slug'] }}" class="btn btn-primary">
                    تفاصييل المنتج
                </a>
            </div>
            <div class="card-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>اسم التاجر</th>
                            <th>سعر المزاد</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product_eneba['S_product']['auctions']['edges'] as $auction )
                            <tr>
                                <td> {{  $auction['node']['merchantName'] }}</td>
                                <td> {{  FormatePrice($auction['node']['price']['amount']) }}</td>
                                <td> {{  $auction['node']['belongsToYou'] == true ? 'مزادك' : '-' }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <div style="padding: 26px;">
                    @if($product_eneba['S_product']['auctions']['pageInfo']['hasNextPage'] == true)
                        <a class="btn btn-danger" href="{{ route('application.auctions.create',['eneba_id' =>$eneba_id,'next' => $product_eneba['S_product']['auctions']['pageInfo']['endCursor'] ]) }}">
                            التالي
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom_style')
<style>
    .card-body .inner-auction-info div.mb-3{
        background-color: #E0F2F1;
        padding: 16px 16px 16px 50px !important;
    }
</style>
@endpush