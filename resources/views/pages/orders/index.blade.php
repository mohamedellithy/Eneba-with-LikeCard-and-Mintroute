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
                        <th>تاريخ الطلبية</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0 alldata">
                    @foreach($eneba_orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->status_order }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{  $eneba_orders->links()  }}
    </div>
</div>
@endsection

