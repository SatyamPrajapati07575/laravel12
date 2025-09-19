@extends('layouts.app')

@section('title', $title ?? 'User Registration')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center mb-3"><i class="fa-solid fa-user-plus"></i> Register</h3>

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label><br>
                        <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                        Male
                        <input type="radio" name="gender" value="female"
                            {{ old('gender') == 'female' ? 'checked' : '' }}> Female
                        <input type="radio" name="gender" value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                        Other
                        @error('gender')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <select name="country_id" id="country" class="form-control">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <select name="city_id" id="city" class="form-control">
                            <option value="">Select City</option>
                            @if (!empty($cities))
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('city_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="terms" value="1" class="form-check-input" id="terms"
                            {{ old('terms') ? 'checked' : '' }}>
                        <label for="terms" class="form-check-label">Accept Terms & Conditions</label>
                        @error('terms')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-paper-plane"></i> Register
                    </button>
                </form>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            let oldCityId = "{{ old('city_id') }}";
            let oldCountryId = "{{ old('country_id') }}";

            // If country_id is old (due to validation), auto-load cities
            if (oldCountryId) {
                $.get('/api/cities/' + oldCountryId, function(data) {
                    $('#city').empty().append('<option value="">Select City</option>');
                    $.each(data, function(i, city) {
                        let selected = (city.id == oldCityId) ? 'selected' : '';
                        $('#city').append('<option value="' + city.id + '" ' + selected + '>' + city
                            .name + '</option>');
                    });
                });
            }

            // On country change
            $('#country').change(function() {
                var country_id = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>');

                if (country_id) {
                    $.get('/api/cities/' + country_id, function(data) {
                        $.each(data, function(i, city) {
                            $('#city').append('<option value="' + city.id + '">' + city
                                .name + '</option>');
                        });
                    });
                }
            });
        });
    </script>
@endpush
