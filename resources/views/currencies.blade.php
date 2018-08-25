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
        {{--{{ var_dump($errors) }}--}}
        <div id="errors"></div>
    </div>

    <div class="alert alert-secondary text-center" role="alert">
        История операций
    </div>

    @if(isset($operations))
        <table class="table" id="lastOperations">
            <thead>
            <tr class="table-active">
                <th scope="col">From</th>
                <th scope="col">Price</th>
                <th scope="col">To</th>
                <th scope="col">Price</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            @foreach($operations as $operation)
                <tr class="table-active">
                    <td>{{ $operation->from_currency_id }}</td>
                    <td>{{ $operation->from_price }}</td>
                    <td>{{ $operation->to_currency_id }}</td>
                    <td>{{ $operation->to_price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <script src="{{URL::asset('js/currencies.js')}}"></script>
@endsection


