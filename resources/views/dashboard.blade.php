@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-sm-6 text-left">
                <P style="font-size:30px;" class="card-category">BEM VINDO!</P>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>
    <script>
        $(document).ready(function() {
          demo.initDashboardPageCharts();
        });
    </script>
@endpush
