@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('third_party_stylesheets')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Crear Usuario <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Nombre <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password">Contraseña <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmar Contraseña <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role">Rol <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" id="role" required>
                                    <option value="" selected disabled>Seleccionar Rol</option>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        @if (auth()->user()->hasRole(2) || $role->id !== 2)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="is_active">Estado <span class="text-danger">*</span></label>
                                <select class="form-control" name="is_active" id="is_active" required>
                                    <option value="" selected disabled>Seleccionar Estado</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Imagen de Perfil <span class="text-danger">*</span></label>
                                <input id="image" type="file" name="image" data-max-file-size="500KB">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('third_party_scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endsection

@push('page_scripts')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        const fileElement = document.querySelector('input[id="image"]');
        const pond = FilePond.create(fileElement, {
            acceptedFileTypes: ['image/png', 'image/jpg', 'image/jpeg'],
            labelIdle: 'Arrastra y suelta tu Imagen o <span class="filepond--label-action"> Click Aquí </span>',
            labelMaxFileSizeExceeded: 'Tamaño máximo de imagen excedido.',
            labelMaxFileSize: 'El tamaño máximo es {filesize}',
            labelFileTypeNotAllowed: 'Tipo de archivo no permitido. ',
            fileValidateTypeLabelExpectedTypes: 'Se esperan los formatos PNG, JPEG o JPG',
            labelFileProcessingComplete: 'Se subió la imagen',
            labelTapToUndo: 'Click para cancelar',
            labelTapToCancel: 'Click para cancelar',
            labelFileProcessing: 'Subiendo',
            credits: false,
        });
        FilePond.setOptions({
            server: {
                url: "{{ route('filepond.upload') }}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }
        });
    </script>
@endpush
