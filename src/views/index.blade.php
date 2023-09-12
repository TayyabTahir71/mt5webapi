@extends('MT5WebApi::layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>MetaQuotes WebAPI</h1>
                <p>{{ $here }}</p>
                @if($account)
                    {{ $account->Login }}
                @endif
            </div>
        </div>
    </div>
@endsection
