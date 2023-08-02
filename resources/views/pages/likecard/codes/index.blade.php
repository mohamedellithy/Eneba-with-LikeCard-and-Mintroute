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
            <form id="filter-data" method="get">
                <div class="d-flex filters-fields">
                    <div class="nav-item d-flex align-items-center m-2" >
                        <form id="filter-data" method="get">
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
                    <div class="nav-item d-flex align-items-center m-2" >
                        <select name="product_id" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-lg">
                            <option>المنتجات</option>
                            @isset($products)
                                @foreach($products as $product)
                                    <option value="{{ $product['productId'] }}">{{ $product['productName'] }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                </div>
            </form>
            <br/>
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
