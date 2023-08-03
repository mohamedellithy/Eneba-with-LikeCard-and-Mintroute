@extends('master')
@php
$category_id = request()->query('category_id') ?: null;
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
                    <form id="filter-data" method="get">
                        <label>التصنيفات</label>
                        <select name="category_id" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-lg">
                            <option>تصنيفات المنتجات</option>
                            @isset($categories)
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}" @isset($category_id) @if($category_id == $category['id']) selected @endif @endisset>{{ $category['categoryName'] }}</option>
                                    @isset($category['childs'])
                                        @foreach($category['childs'] as $child)
                                            <option value="{{ $child['id'] }}" @isset($category_id) @if($category_id == $child['id']) selected @endif @endisset> -- {{ $child['categoryName'] }}</option>
                                        @endforeach
                                    @endisset
                                @endforeach
                            @endisset
                        </select>
                    </form>
                </div>
            </div>
            <form method="post" action="{{ route('application.likecard.store_codes') }}">
                @csrf
                <input type="hidden" value="{{ $category_id }}" name="category_id">
                <div class="d-flex card-body card-body-form">
                    <div class="mb-3">
                        <label>اسم المنتج</label>
                        <select name="product_id" id="largeSelect" class="form-select form-select-lg">
                            <option value="">المنتجات</option>
                            @isset($products)
                                @forelse($products as $product)
                                    <option value="{{ $product['productId'] }}">{{ $product['productName'] }}</option>
                                @empty
                                @endforelse
                            @endisset
                        </select>
                    </div>
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
                            <th>الصورة</th>
                            <th>رقم المنتج</th>
                            <th>اسم المنتج</th>
                            <th>نوع المنتج</th>
                            <th>كود المنتج</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @forelse($codes as $code)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    <img src="{{ $code->product_image ?: '' }}" />
                                </td>
                                <td>{{ $code->product_id   ?: '-' }}</td>
                                <td>{{ $code->product_name ?: '-' }}</td>
                                <td>{{ $code->product_type ?: '-' }}</td>
                                <td>{{ $code->code         ?: '-' }}</td>
                                <td>{{ $code->status       ?: '-' }}</td>
                                <td></td>
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
</style>
@endpush