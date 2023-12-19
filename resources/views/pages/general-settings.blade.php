@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <form method="post" action="{{ route('application.save_settings') }}">
            @csrf
            <h4>اعدادات المنصة</h4>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            {{ exchange_currency(20) }}
                            <label class="label-control" style="line-height: 3em;">معدل تحويل العملة من الدولار الى اليورو</label>
                            <input class="form-control" type="number" step="0.01" value="{{ get_settings('exchange_rate') }}" name="exchange_rate" placeholder="معدل تحويل العملة من الدولار الى اليورو"/>
                        </div>
                        <br/>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-sm">
                                حفظ الاعدادات
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
