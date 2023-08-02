@extends('master')
@php
$category_id = request()->query('category_id') ?: null;
@endphp
@push('custom_style')
<style>
    .card-body-form{
        display: flex !important;
        flex-wrap: wrap;
        justify-content: space-evenly;
        align-items: flex-end;
    }
    .card-body-form .mb-3{
        width: 250px;
    }
    .card-body-form .mb-3 label{
        padding-bottom: 10px;
    }
    .card-body-form .mb-3 input
    {
        height: 50px;
    }
</style>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            المنتجات
        </h4>
        <!-- Basic Bootstrap Table -->
        <form method="post" action="{{ route('application.likecard.store_codes') }}">
            <input type="hidden" @isset($category_id) value="{{ $category_id }}" @endisset name="category_id"/>
            @csrf
            <div class="card" style="padding-top: 3%;">
                <div class="d-flex card-body card-body-form">
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
                    <div class="mb-3">
                        <label>اسم المنتج</label>
                        <select name="product_id" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-lg">
                            <option value="">المنتجات</option>
                            @isset($products)
                                @foreach($products as $product)
                                    <option value="{{ $product['productId'] }}">{{ $product['productName'] }}</option>
                                @endforeach
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
            </div>
        </form>
        <br/>
        <div class="card" style="padding-top: 3%;">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>السعر</th>
                            <th>المتوفر</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@endsection
