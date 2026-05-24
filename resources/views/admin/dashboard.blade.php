@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Guru</h5>
                    <p class="card-text display-4">{{ $totalGuru }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mata Pelajaran</h5>
                    <p class="card-text display-4">{{ $totalMapel }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Kelas</h5>
                    <p class="card-text display-4">{{ $totalKelas }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jam Pelajaran</h5>
                    <p class="card-text display-4">{{ $totalJam }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection