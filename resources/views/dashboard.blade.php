@extends('partials.master')

@section('content')
<h1>{{ config('app.name') }}</h1>
<div class="table h20">
    <div class="eye-suit center middle">
        <button @click="suitEyes" title="Suit your eyes"><i class="material-icons">palette</i></button>
        <button @click="toggleGui" title="Call GUI"><i class="material-icons">local_library</i></button>
    </div>
</div>

<content-wrapper></content-wrapper>

<transition name="gui">
    <gui v-if="showGui"></gui>
</transition>

@endsection

@section('handover')
<script>
    window.handover = {
        user: {!! $user !!},
        userCollection: {!! json_encode($userCollection) !!},
        library: {!! json_encode($library) !!},
        _token: '{{ csrf_token() }}'
    }
</script>
@endsection