@extends('master')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        المزادات
    </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card" style="padding-top: 3%;">
        <div class="card-body card-category-frmae">
            <div class="mb-3">
                <form id="filter-data" method="get">
                    <label>التصنيفات</label>
                    <select name="category_id" id="largeSelect" class="form-select form-select-lg">
                        <option>تصنيفات المنتجات</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection