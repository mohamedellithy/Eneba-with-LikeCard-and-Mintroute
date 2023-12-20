@extends('master')
@php
    $name = request('name');
@endphp
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        رقم الطلبية #{{ $eneba_order->order_id  }}
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
                                <th>رقم المنتج</th>
                                <th>عدد الاكواد المطلوبة</th>
                                <th>سعر الوحدة</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0 alldata">
                            @foreach($auctions as $auction_item)
                                <tr>
                                    <td>{{ $auction_item->auction_details->auction }}</td>
                                    <td>{{ $auction_item->auction_details->product_id }}</td>
                                    <td>{{ $auction_item->key_count_required }}</td>
                                    <td>{{ $auction_item->unit_price / 100 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding: 26px;">
                    {{  $auctions->links()  }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="padding-top: 3%;">
                <ul class="u-lists">
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">رقم الطلبية</p>
                        <strong>{{ $eneba_order->order_id }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">حالة الطلب</p>
                        <strong>{{ $eneba_order->status_order }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">تاريخ الطلبية</p>
                        <strong>{{ $eneba_order->created_at }}</strong>
                    </li>
                    <li style="padding: 15px;">
                        <p style="margin-bottom: 0px;">تحديث الطلبية</p>
                        <strong>{{ $eneba_order->updated_at }}</strong>
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
