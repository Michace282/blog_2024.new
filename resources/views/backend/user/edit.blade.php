@extends('layouts.app')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('users.update', $users->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#login" data-toggle="tab"><i class="fas fa-key"></i> Login</a>
                        </li>
                    </ul>
                </h3>
                <div class="card-tools">
                </div>
            </div><!-- /.card-header -->

            <div class="card-body">
                <div class="tab-content p-0">

                    <div class="chart tab-pane active" id="login">
                        <div class="row">
                            <div class="col col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="{{ $users->email }}">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        value="" placeholder="diisi jika ingin mengganti password"
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="profile_image">Foto Baru</label>
                                    <br>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="profile_image">Preview Foto Baru</label>
                                    <br>
                                    <img id="imagePreview" src="" alt="Preview Gambar" style="display: none;"
                                         class="img-fluid">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="alert alert-danger alert-dismissible" bis_skin_checked="1">
                                    <h5 class="text-bold"><i class="icon fas fa-info"></i> PERHATIAN!</h5>
                                    <ul>
                                        <li>Size Foto maksimum = 2mb</li>
                                        <li>Resolusi Foto minimum = 500x500 pixels</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                    Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger"><i class="fa fa-sync"></i> Reset</button>
                <a href="{{ route('users.index') }}" name="reset" class="btn btn-dark">
                    <i class="fa fa-arrow-left"></i> Save</a>
            </div>
            <!-- /.card-body -->
        </div>
    </form>
@endsection

@section('script_addon')
    <!-- select2 -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        // Menonaktifkan tombol submit setelah diklik
        $('form').submit(function() {
            $('#submit').attr('disabled', 'disabled');
            return true; // proses form
        });

        $('#company_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Dinas / Instansi --",
        });
        $('#province_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Provinsi --",
        });
        $('#city_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kota --",
        });
        $('#district_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kecamatan --",
        });
        $('#village_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kelurahan / Desa --",
        });

        const profile_image = document.getElementById("profile_image");
        const imagePreview = document.getElementById("imagePreview");

        profile_image.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "";
                imagePreview.style.display = "none";
            }
        });

        // Show region data
        $(document).ready(function() {
            // show province
            $('#province_id').change(function() {
                var provinceId = $(this).val();
                $.get('/region/getCity', {
                    province_id: provinceId
                }, function(data) {
                    $('#city_id').empty();
                    $('#city_id').append('<option value="">Silahkan Pilih Kota</option>');
                    $.each(data, function(key, value) {
                        $('#city_id').append('<option value="' + value.code + '">' + value
                            .name + '</option>');
                    });
                });
            });

            // show city based on province id
            $('#city_id').change(function() {
                var cityId = $(this).val();
                $.get('/region/getDistrict', {
                    city_id: cityId
                }, function(data) {
                    $('#district_id').empty();
                    $('#district_id').append('<option value="">Silahkan Pilih Kecamatan</option>');
                    $.each(data, function(key, value) {
                        $('#district_id').append('<option value="' + value.code + '">' +
                            value.name + '</option>');
                    });
                });
            });

            // show district based on city code
            $('#district_id').change(function() {
                var districtId = $(this).val();
                $.get('/region/getVillage', {
                    district_id: districtId
                }, function(data) {
                    $('#village_id').empty();
                    $('#village_id').append('<option value="">Silahkan Pilih Kecamatan</option>');
                    $.each(data, function(key, value) {
                        $('#village_id').append('<option value="' + value.code + '">' +
                            value.name + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
