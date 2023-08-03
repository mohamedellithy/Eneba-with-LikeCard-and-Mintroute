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
                    {{-- <div class="nav-item d-flex align-items-center m-2" >
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" class="search form-control border-0 shadow-none" placeholder="البحث ...."
                            @isset($search) value="{{ $search }}" @endisset id="search" name="search"/>
                    </div> --}}
                    <div class="nav-item d-flex align-items-center m-2" >
                        <select name="filter" id="largeSelect"  onchange="document.getElementById('filter-data').submit()" class="form-select form-select-lg">
                            <option>فلتر المنتجات</option>
                            <option value="high-price" @isset($filter) @if($filter == 'high-price') selected @endif @endisset>الاعلي سعرا</option>
                            <option value="low-price"  @isset($filter) @if($filter == 'low-price') selected @endif @endisset>الاقل سعرا</option>
                            <option value="more-sale"  @isset($filter) @if($filter == 'more-sale') selected @endif @endisset>الاكثر طلبا</option>
                            <option value="less-sale"  @isset($filter) @if($filter == 'less-sale') selected @endif @endisset>الاقل طلبا</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="d-flex">
                    <div class="nav-item d-flex align-items-center m-2" >
                        <label style="padding: 0px 10px;color: #636481;">المعروض</label>
                        <select name="rows" onchange="document.getElementById('filter-data').submit()" id="largeSelect" class="form-select form-select-sm">
                            <option>10</option>
                            <option value="50" @isset($rows) @if($rows == '50') selected @endif @endisset>50</option>
                            <option value="100" @isset($rows) @if($rows == '100') selected @endif @endisset>100</option>
                        </select>
                    </div>
                </div> --}}
            </form>
            <br/>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>تاريخ الاطلاق</th>
                            <th>تاريخ الانشاء</th>
                            <th>نوع المنتج</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @foreach($products['result']['edges'] as $product)
                            <tr>
                                <td> {{  $product['node']['name'] }}</td>
                                <td> {{  $product['node']['releasedAt'] }}</td>
                                <td> {{  $product['node']['createdAt'] }}</td>
                                <td> {{  $product['node']['type']['value'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding: 26px;">
                    @if($products['result']['pageInfo']['hasNextPage'] == true)
                        <a class="btn btn-danger" href="{{ route('application.eneba.products',['next' => $products['result']['pageInfo']['endCursor'] ]) }}">
                            التالي
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
    </div>
@endsection
