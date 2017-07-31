@extends('main')

@section('content')
    // vuetable-2 dependencies
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.2.6/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.16.1/axios.min.js"></script>
    // vuetable-2
    <script src="https://unpkg.com/vuetable-2@1.6.0"></script>
    Vue.use(Vuetable)
    <div class="container">
        <my-vuetable></my-vuetable>
    </div>
@endsection
