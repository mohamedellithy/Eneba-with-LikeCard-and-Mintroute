@extends('master')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="label-control" style="line-height: 3em;">معدل تحويل العملة من الدولار الى اليورو</label>
                        <input class="form-control" type="number" steps="0.01" name="exchange_rate" placeholder="معدل تحويل العملة من الدولار الى اليورو"/>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm">
                            حفظ الاعدادات
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
