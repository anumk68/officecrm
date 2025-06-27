    @include('layouts.app')
    @include('layouts.header')
    @include('layouts.left-sidebar')
    <h1>welcome to dashboard,{{ Auth::user()->name}}</h1>
    <form method="POST" action={{route('logout')}}>
        @csrf
        <button type="submit">Logout</button>
    </form>
    @include('layouts.script')
