@extends('users.layout', ['tab' => 'profile'])

@section('main')
    <form action="{{ route('users.update') }}" method="POST">
        @csrf
        @method('PATCH')

        @form($form)
    </form>
@endsection
