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
            {{-- <form method="post" action="{{ route('application.likecard.store_codes') }}">
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
            </form> --}}
        </div>
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
                        @isset($products)
                            @forelse($products as $product)
                                <tr>
                                    <td class="">
                                        <img src="{{ $product['productImage'] }}" alt="Avatar"
                                        class="">
                                    </td>
                                    <td class="width-30">{{ $product['productName'] }}</td>
                                    <td>
                                    {{ $product['productPrice'] }} {{ $product['productCurrency'] }}
                                    </td>

                                    <td>
                                        @if ($product['available'] == true)
                                            <span class="badge bg-label-success me-1">متاح</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">غير متاح</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="dropdown-item" href="{{ $product['productId'] }}"><i
                                                class="fa-regular fa-eye me-2"></i></i>عرض
                                        </a>
                                    </td>
                                    {{-- <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                            <div class="dropdown-menu">
                                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.products.edit', $product->id) }}"><i
                                                            class="bx bx-edit-alt me-2"></i>
                                                        تعديل</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx bx-trash me-2"></i>حذف
                                                    </button>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.products.show', $product->id) }}"><i
                                                            class="fa-regular fa-eye me-2"></i></i>عرض

                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                            @endforelse
                        @endisset
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
