@extends('master')
@php
    $name = request('name');
@endphp
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        الطلبات
    </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card" style="padding-top: 3%;">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>رقم الطلب اينيبا</th>
                        <th>حالة الطلب</th>
                        <th>تاريخ التحديث</th>
                        <th>تاريخ الطلبية</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0 alldata">
                    @foreach($eneba_orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->status_order }}</td>
                            <td>{{ $order->updated_at }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                <a href="{{ route('application.single_eneba_order',['id' => $order->id]) }}" class="btn btn-success btn-sm ">
                                    عرض تفاصيل الطلبية
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 26px;">
            {{  $eneba_orders->links()  }}
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
