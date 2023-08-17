@extends('master')
@php 
$eneba_id   = request('eneba_id'); 
$prices     = GetAuctionPrices($eneba_id);
$low_price  = $prices->min('amount') / 100;
$high_price = $prices->max('amount') / 100;
@endphp
@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Basic Layout</h5>
                <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">السعر من لايك كارد</label>
                        <input type="text" class="form-control" id="basic-default-fullname" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-company">الحد الادني للتسعير</label>
                        <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-email">الحد الأقصي للتسعير</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="basic-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
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
                                <td> {{  $auction['node']['price']['amount'].' '.$auction['node']['price']['currency'] }}</td>
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
