@extends('master')
@php
$eneba_id   = $auction->product_id;
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
                @if(count($likecard_product) > 0 )
                    <form class="inner-auction-info" method="post" action="{{ route('application.auctions.update',$auction->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="flexSwitchCheckChecked" @if($auction->status == 1) checked @endif>
                            <label class="form-check-label" for="flexSwitchCheckChecked">تشغيل المزاد</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">السعر من لايك كارد ( {{ $likecard_product[0]['productCurrency'] }} ) </label>
                            <input type="text" class="form-control" value="{{ $likecard_product[0]['productPrice'] }}" id="basic-default-fullname" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-company">الحد الادني للتسعير ( EUR )</label>
                            <input type="text" class="form-control" name="min_price" value="{{ FormatePrice($auction->low_price ?: $low_price,false) }}" id="basic-default-company" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-company">سعر الحالى  ( EUR )</label>
                            <input type="text" class="form-control" name="current_price" value="{{ $auction->current_price ?: $likecard_product[0]['productPrice'] }}" id="basic-default-company" placeholder="" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-email">الحد الأقصي للتسعير ( EUR )</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="basic-default-email" name="max_price" value="{{ FormatePrice($auction->high_price ?: $high_price,false) }}" class="form-control"  aria-label="john.doe" aria-describedby="basic-default-email2" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-email">عدد الأكواد</label>
                            <div class="input-group input-group-merge">
                                <input type="number" id="basic-default-email" name="count_cards" value="{{ $auction->count_cards ?: 1 }}" class="form-control"  aria-label="john.doe" aria-describedby="basic-default-email2" required>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="automation" value="1" id="flexSwitchCheckChecked" @if($auction->automation == 1) checked @endif>
                            <label class="form-check-label" for="flexSwitchCheckChecked">تغير السعر بشكل اتوماتك</label>
                        </div>
                        <div class="mb-3">
                            <label for="defaultSelect" class="form-label">مدة مراجعة السعر</label>
                            <select id="defaultSelect" name="change_time" class="form-select">
                                <option value="5"   @if($auction->change_time == 5)   selected @endif>5 دقائق</option>
                                <option value="10"  @if($auction->change_time == 10)  selected @endif>10 دقائق</option>
                                <option value="15"  @if($auction->change_time == 15)  selected @endif>15 دقائق</option>
                                <option value="60"  @if($auction->change_time == 60)  selected @endif>ساعة</option>
                                <option value="120" @if($auction->change_time == 120) selected @endif>ساعتين</option>
                                <option value="180" @if($auction->change_time == 180) selected @endif>3 ساعات</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-email">قيمة التغير فى السعر</label>
                            <div class="input-group input-group-merge">
                                <input type="text" name="price_step" id="basic-default-email"  value="{{ $auction->price_step ?: '0.01' }}" class="form-control" aria-label="john.doe" aria-describedby="basic-default-email2" required>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="autoRenew" value="1" id="flexSwitchCheckChecked" @if($auction->autoRenew == 1) checked @endif>
                            <label class="form-check-label" for="flexSwitchCheckChecked">المخزون ثابت</label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            تعديل المزاد
                        </button>
                    </form>
                @else
                   <div class="alert alert-danger">
                    منتجك غير مربوط بمنتج لايك كارد الرحاء ربط منتجك بمنتج لايك كارد للبدء فى الدحول للمزاد
                   </div>
                   <a class="btn btn-danger" href="{{ route('application.eneba.get_single_product',$eneba_id) }}">
                        ربط المنتج بمنتج لايك كارد
                   </a>
                @endif
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
                        @forelse($product_eneba['S_product']['auctions'][0]['competition']['edges'] as $auction )
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
        background-color: #EFEBE9;
        padding: 16px 16px 16px 50px !important;
    }
</style>
@endpush