<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Pasien Form</h1>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('test.submit') }}">
            @csrf
            
            <div class="mb-3">
                <label for="nama_pasien" class="form-label">Nama Pasien</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
            </div>
            
            <div class="mb-3">
                <label for="diagnosa" class="form-label">Diagnosa</label>
                <input type="text" class="form-control" id="diagnosa" name="diagnosa" required>
            </div>
            
            <div class="mb-3">
                <label for="diagnosa_kode" class="form-label">Kode Diagnosa</label>
                <input type="text" class="form-control" id="diagnosa_kode" name="diagnosa_kode">
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Test</button>
        </form>
        
        <hr>
        
        <h3>Current Data:</h3>
        <p>Total Pasiens: {{ App\Models\Pasien::count() }}</p>
        
        @if(App\Models\Pasien::count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Diagnosa</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\Pasien::latest()->take(5)->get() as $pasien)
                        <tr>
                            <td>{{ $pasien->id }}</td>
                            <td>{{ $pasien->nama_pasien }}</td>
                            <td>{{ $pasien->diagnosa }}</td>
                            <td>{{ $pasien->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
