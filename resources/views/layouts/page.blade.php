@extends('app')

@section('title')
    Index
@endsection

@section('content')
    <div class="row">
        <table>
            <tr>
                <th><p><strong>Key</strong></p></th>
                <th><p><strong>Value</strong></p></th></tr>
            <tr>
                @foreach ($data as $key => $value)
                    @if(isset($names[$key]))
                    <tr>
                        <td><strong>{{$names[$key]}}</strong></td>
                        <td>{!! $value !!}</td>
                    </tr>
                @endif
                @endforeach
        </table>
    </div>
@endsection