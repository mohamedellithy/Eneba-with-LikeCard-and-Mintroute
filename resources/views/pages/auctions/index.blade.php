@extends('master')

@push('custom_style')
<style>
    .container-fields{
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: flex-start;
    }
    .container-fields .form-group:first-child{
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
            <form method="get" >
                <div class="mb-3 container-fields">
                    <div class="form-group">
                        <label>المنتجات </label>
                        <input type="text" name="name" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            البحث عن المنتج
                        </button>
                    </div>
                </div>
            </form>
        </div>
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('custom_script')
<script>
    // jQuery('document').ready(function(){
    //     jQuery('.search_on_eneba_prods').on('click',function(){
    //         let product_name = jQuery('.product_name').val();
    //         $.ajaxSetup({
    //             headers:{
    //                 'X-CSRF-TOKEN':jQuery('meta[name="csrf-token"]').attr('content')
    //             }
    //         });

    //         $.ajax({
    //             type:'POST',
    //             url:"{{ route('application.search-on-eneba-products') }}",
    //             data:{
    //                 eneba_product_name:product_name
    //             },
    //             success:function(response){
    //                 console.log(response);
    //             }
    //         })
    //     });
    // });
</script>
@endpush