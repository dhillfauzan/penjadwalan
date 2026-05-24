@extends('layout.admin')
@section('content')
<div class="container">
    <h1>Edit Jam Pelajaran</h1>
    <a href="{{ route('admin.jam-pelajaran.index') }}" class="btn btn-secondary mb-3">Kembali</a>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.jam-pelajaran.update', $jamPelajaran) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="Senin" {{ $jamPelajaran->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ $jamPelajaran->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ $jamPelajaran->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ $jamPelajaran->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ $jamPelajaran->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jam ke-</label>
                    <input type="number" name="jam_ke" class="form-control" value="{{ $jamPelajaran->jam_ke }}" required>
                </div>
                <div class="mb-3">
                    <label>Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="form-control" value="{{ $jamPelajaran->waktu_mulai }}" required>
                </div>
                <div class="mb-3">
                    <label>Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="form-control" value="{{ $jamPelajaran->waktu_selesai }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection