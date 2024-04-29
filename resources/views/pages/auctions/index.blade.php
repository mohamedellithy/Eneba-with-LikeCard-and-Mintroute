@extends('master')
@php
    $name = request('name');
@endphp
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
                        <input type="text" name="name" value="{{ $name }}" class="form-control" required/>
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
    @if(isset($name))
        <div class="card" style="padding-top: 3%;">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>كود المنتج</th>
                            <th>الاسم</th>
                            <th>نوع المنتج</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        <tbody>
                            @forelse($products['result']['edges'] as $product)
                                <tr>
                                    <td> {{  $product['node']['id'] }}</td>
                                    <td> {{  $product['node']['name'] }}</td>
                                    <td> {{  $product['node']['type']['value'] }}</td>
                                    <td>
                                        @if(in_array($product['node']['id'],$auctions))
                                           <span class="badge bg-warning">
                                             مزايد عليها
                                           </span>
                                        @else
                                            <a href="{{ route('application.auctions.create',$product['node']['id']) }}" class="btn btn-info btn-sm">
                                                اختيار المنتج
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </tbody>
                </table>
            </div>
            <div style="padding: 26px;">
                @if($products['result']['pageInfo']['hasNextPage'] == true)
                    <a class="btn btn-danger" href="{{ route('application.auctions',['name' =>$name,'next' => $products['result']['pageInfo']['endCursor'] ]) }}">
                        التالي
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="card" style="padding-top: 3%;">
            <div class="table-responsive">
                <table class="table table-striped" style="word-break: break-word;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>رقم المزاد</th>
                            <th>اسم منتج اينيبا</th>
                            <th>اسم منتج لايك كارد</th>
                            <th>عدد الأكواد</th>
                            <th>سعر المزاد</th>
                            <th>حالة المزاد</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 alldata">
                        @forelse($auctions as $auction)
                            <tr>
                                <td></td>
                                <td>
                                    @if($auction->auction)
                                        {{ $auction->auction }} 
                                    @else
                                         لم يدرج للمزاد بعد
                                    @endif
                                </td>
                                <td>
                                    {{ eneba_single_product($auction->product_id)['S_product']['name'] }}
                                </td>
                                <td>
                                    {{ likecard_single_product($auction->product->likecard_prod_id)['data'][0]['productName'] }}
                                </td>
                                <td>
                                    {{ $auction->count_cards.' أكواد ' }}
                                </td>
                                <td>
                                    {{ $auction->current_price.' EUR' }}
                                </td>
                                <td>
                                    <i class='bx bxs-circle' @if($auction->status == 1) style="color:green" @else style="color:red" @endif ></i>
                                    @if($auction->status == 1)
                                    مفعل
                                    @else
                                    متوقف
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown" >
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" style="padding: 11px;">
                                            @if($auction->status == 1)
                                                <form method="post" action="{{ route('application.auctions.update_status',$auction->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="0" />
                                                    <button class="btn btn-danger btn-sm dropdown-item">
                                                        ايقاف المزاد
                                                    </button>
                                                </form>
                                            @else
                                                <form method="post" action="{{ route('application.auctions.update_status',$auction->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="1" />
                                                    <button class="btn btn-success btn-sm dropdown-item">
                                                        تشغيل المزاد
                                                    </button>
                                                </form>
                                            @endif
                                            <a class="btn btn-info btn-sm dropdown-item" target="_blank" href="{{ "https://www.eneba.com/".eneba_single_product($auction->product_id)['S_product']['slug'] }}">
                                                  عرض المزاد
                                            </a>
                                            <a class="btn btn-warning btn-sm dropdown-item"  href="{{ route('application.auctions.edit',$auction->id) }}">
                                                تعديل المزاد
                                          </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <div style="padding: 26px;">
                    {{  $auctions->links()  }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
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
