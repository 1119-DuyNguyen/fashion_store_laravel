@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (Session::has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (Session::has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
