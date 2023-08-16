@extends('master')
@php
$eneba_id = request('eneba_id') ?: null;
@endphp

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            المنتجات
        </h4>
        <!-- Basic Bootstrap Table -->
        <div class="card" style="padding-top: 3%;">
            <div class="card-body card-category-frmae">
                <div class="mb-3">
                    <span class="badge bg-danger">
                        منتج اينيبا
                    </span>
                    <h4 style="line-height: 4em;">
                        {{ $product_eneba['S_product']['name'] }}
                    </h4>
                    <a href="{{ 'https://www.eneba.com/'.$product_eneba['S_product']['slug'] }}" target="_blank" class="btn btn-warning">
                       تفاصيل المنتج
                    </a>
                </div>
            </div>
            <form method="post" action="{{ route('application.eneba.store_codes') }}">
                @csrf
                <input type="hidden" value="{{ $eneba_id }}" name="product_id">
                <div class="d-flex card-body card-body-form">
                    <div class="mb-3">
                        <label>كود المنتج</label>
                        <input type="text" name="code" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            اضافة كود جديد
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <br/>
        <div class="card" style="padding-top: 3%;">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th>كود المنتج</th>
                            <th>كود مستخدم</th>
                            <th>حالة الكود</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @forelse($eneba_offline_codes as $eneba_code)
                            <tr>
                                <td>{{ $eneba_code->id }}</td>
                                <td>{{ $eneba_code->code }}</td>
                                <td>{{ $eneba_code->status_used == 'unused' ? 'غير مستخدم' :'مستخدم' }}</td>
                                <td>{{ $eneba_code->status == 'allow' ? 'مسموح' :'محظور' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="btn btn-warning btn-sm dropdown-item">
                                                استخدام الكود
                                            </button>
                                            @if($code->status == 'allow')
                                                <button class="btn btn-danger btn-sm dropdown-item">
                                                    توقيف الكود
                                                </button>
                                            @elseif($code->status != 'allow')
                                                <button class="btn btn-success btn-sm dropdown-item">
                                                    اتاحة الكود
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@endsection


@push('custom_style')
<style>
    .card-body-form{
        display: flex !important;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: flex-end;
    }
    .card-body-form .mb-3{
        width: 280px;
        margin-left: 3%;
    }
    .card-category-frmae{
        width: 53%;
    }

    .card-body-form .mb-3 label,
    .card-category-frmae label{
        padding-bottom: 10px;
    }
    .card-body-form .mb-3 input ,
    .card-category-frmae mb3 input
    {
        height: 50px;
    }
    .dropdown-menu{
        padding: 10px;
    }
    .dropdown-menu button{
        margin:2px;
    }
</style>
@endpush
