@extends('master')
@php
    $name = request('name');
    $response_order = json_decode($provider_order->response,true);
    $LikeCard = new LikeCard();
@endphp
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        رقم الطلبية #{{ $provider_order->provider_order_id  }}
    </h4>
    <!-- Basic Bootstrap Table -->
    <div class="row">
        <div class="col-md-8">
            <div class="card" style="padding-top: 3%;">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>رقم المزاد</th>
                                <th>اسم المنتج</th>
                                <th>عدد الاكواد المطلوبة</th>
                                <th>سعر الوحدة</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 alldata">
                            @foreach($response_order['orders'] as $order)
                                <tr>
                                    <td>{{ $order->orderNumber }}</td>
                                    <td>{{ $order->orderFinalTotal }}</td>
                                    <td>{{ $order->productName }}</td>
                                    <td>{{ $LikeCard->decryptSerial($order->serials[0]['serialNumber']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="padding-top: 3%;">
                <ul class="u-lists">
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">رقم الطلبية</p>
                        <strong>{{ $provider_order->provider_order_id }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">رقم المزاد</p>
                        <strong>{{ $provider_order->auction }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">تاريخ الطلبية</p>
                        <strong>{{ $provider_order->created_at }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">تحديث الطلبية</p>
                        <strong>{{ $provider_order->updated_at }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">اسم منتج اينيبا</p>
                        {{ eneba_single_product($provider_order->auction_details->product_id)['S_product']['name'] }}
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom_style')
<style>
    .container-fields{
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: flex-start;
    }
    .container-fields .form-group:first-child{
        width: 50%;
        margin: auto 0px auto 17px;
    }
</style>
@endpush
@push('custom_script')

@endpush
