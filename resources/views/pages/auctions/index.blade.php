@extends('master')

@push('custom_style')
<style>
    .container-fields{
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: flex-start;
    }
    .container-fields .form-group{
        width: 50%;
        margin: auto 0px auto 17px;
    }
</style>
@endpush
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        المزادات
    </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card" style="padding-top: 3%;">
        <div class="card-body card-category-frmae">
            <form id="filter-data" method="get">
                <div class="mb-3 container-fields">
                    <div class="form-group">
                        <label>التصنيفات</label>
                        <input type="text" name="" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <button type="text" name="" class="btn btn-success">
                            البحث عن المنتج
                        </button>
                    </div>
                </div>
            </form>
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