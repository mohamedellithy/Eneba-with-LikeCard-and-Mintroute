@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold d-block mb-1">last orders</h6>
                    <br/>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orders)
                                @isset($orders['data'])
                                    @foreach($orders['data'] as $order)
                                        <tr>
                                            <td>
                                                {{ $order->orderNumber }}
                                            </td>
                                            <td>
                                                {{ $order->orderCurrentStatus }}
                                            </td>
                                            <td>
                                                <a href="#">
                                                    <i class='bx bxs-show'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            @else
                                <tr>
                                    <td colspan="3">No Orders</td>
                                </tr>
                            @endisset
                        </tbody>
                    </table>
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
    .table th , .table td {
        font-size: 0.65rem;
        text-align: center;
    }
</style>
@endsection
