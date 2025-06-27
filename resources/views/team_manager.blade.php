@extends('layouts.app')
@include('layouts.app')
@include('layouts.header')
@section('content')
    <h1>welcome to dashboard,{{ Auth::user()->name}}</h1>
    <form method="POST" action={{route('logout')}}>
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
@include('layouts.footer')
@include('layouts.script')