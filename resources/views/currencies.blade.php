@extends('app')

@section('content')
    <div class="jumbotron">
        <form>
            {{csrf_field()}}

            <div class="row">
                <div class="input-group mb-3 col-sm-5">
                    <div class="input-group-prepend">
                        <select id="leftSelect" class="btn btn-outline-secondary dropdown-toggle">
                            @foreach($valuteProps as $id => $v)
                                <option>{{ $id }}</option>
                            @endforeach
                                <option>RUB</option>
                        </select>
                    </div>
                    <input type="text" id="leftPrice" class="form-control">
                </div>
                <div class="col-sm-2"></div>
                <div class="input-group mb-3 col-sm-5">
                    <div class="input-group-prepend">
                        <select id="rightSelect" class="btn btn-outline-secondary dropdown-toggle">
                            @foreach($valuteProps as $id => $v)
                                <option>{{ $id }}</option>
                            @endforeach
                                <option>RUB</option>
                        </select>
                    </div>
                    <input type="text" id="rightPrice" class="form-control">
                </div>
            </div>

        </form>
    </div>

    @if(isset($transactions))
        <table class="table">
            <thead>
            <tr>
                <th scope="col">From</th>
                <th scope="col">Price</th>
                <th scope="col">To</th>
                <th scope="col">Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->from_currency_id }}</td>
                    <td>{{ $transaction->from_price }}</td>
                    <td>{{ $transaction->to_currency_id }}</td>
                    <td>{{ $transaction->to_price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <script src="{{URL::asset('js/ajax.js')}}"></script>
@endsection


