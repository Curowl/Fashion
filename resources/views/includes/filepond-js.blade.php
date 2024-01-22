<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>

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
            process: "{{ route('filepond.upload') }}",
            revert: "{{ route('filepond.delete') }}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }
    });
</script>
