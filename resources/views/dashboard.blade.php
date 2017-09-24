@extends('partials.master')

@section('content')
<div class="table h20">
    <h1 class="page-title center"><a href="{{ route('dashboard') }}" class="clickable-text">{{ config('app.name', 'Bkooper') }}</a></h1>
    <div class="eye-suit center middle">
        <button @click="suitEyes" title="Suit your eyes"><i class="material-icons">palette</i></button>
        <button @click="toggleGui" title="Call GUI"><i class="material-icons">local_library</i></button>
    </div>
</div>

<content-wrapper></content-wrapper>
<transition name="gui">
    <gui v-if="showGui"></gui>
</transition>

<modal v-if="showModal"></modal>
@endsection

@section('handover')
<script>
    window.handover = {
        authors: {!! json_encode($authors) !!},
        user: {!! $user !!},
        userCollection: {!! json_encode($userCollection) !!},
        library: {!! json_encode($library) !!},
        _token: '{{ csrf_token() }}',
        logout_route: '{{ route('logout') }}'
    }
</script>
@endsection