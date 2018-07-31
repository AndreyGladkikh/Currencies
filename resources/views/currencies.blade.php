@extends('app')

@section('content')
    <div class="jumbotron">
        <form>
            {{csrf_field()}}

            <div class="row">
                <div class="input-group mb-3 col-sm-5">
                    <div class="input-group-prepend">
                        <select id="leftSelect" class="btn btn-outline-secondary dropdown-toggle">
                            @foreach($valuteProps as $v)
                                <option>{{ $v->CharCode }}</option>
                            @endforeach
                                <option>RUB</option>
                        </select>
                        <span class="input-group-text">₽</span>
                    </div>
                    <input type="text" id="leftPrice" class="form-control">
                </div>
                <div class="col-sm-2"></div>
                <div class="input-group mb-3 col-sm-5">
                    <div class="input-group-prepend">
                        <select id="rightSelect" class="btn btn-outline-secondary dropdown-toggle">
                            @foreach($valuteProps as $v)
                                <option>{{ $v->CharCode }}</option>
                            @endforeach
                                <option>RUB</option>
                        </select>
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="text" id="rightPrice" class="form-control">
                </div>
            </div>

        </form>
    </div>
    <script src="{{URL::asset('js/ajax.js')}}"></script>
@endsection


