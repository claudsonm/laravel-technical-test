@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Import a new file') }}</div>

                    <div class="card-body">
                        @include('flash::message')

                        <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="document" class="col-md-4 col-form-label text-md-right">{{ __('File') }}</label>

                                <div class="col-md-6">
                                    <input id="document"
                                           type="file"
                                           class="form-control-file @error('document') is-invalid @enderror"
                                           name="document"
                                           value="{{ old('document') }}"
                                           accept="text/xml"
                                           required >

                                    @error('document')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Process') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
