@extends('partials.master')

@section('content')
<h1>{{ config('app.name') }}</h1>
<div class="table h80">
    <trevacio></trevacio>
</div>
<div class="table h20">
    <div class="eye-suit center bottom">
        <button @click="suitEyes">Suit your eyes</button>
    </div>
</div>
@endsection

@section('handover')
<script>
    window.handover = {
        user: {!! Auth::user() !!}
    }
</script>
@endsection