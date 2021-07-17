@extends('layouts.app')
<style>
    form .form-control:focus {
        border-color: #e35000;
        box-shadow: none;
    }

</style>
@section('metadata')
    <title>Sistema de tickets - Yavirac</title>
    <meta content="Sistema automatizado para atención de tickets de señores y señoritas aspirantes/estudiantes Yavirac. "
        name="description">
    <meta content="nombre y apellido, correo, asunto, detalle" name="keywords">

@endsection
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {!! session('status') !!}
                    </div>
                @endif
                <div align="center">
                    <img src="{{ asset('img/logo.png') }}" style="width:30%; height: auto; margin-bottom:1%" alt="">
                </div>
                <div class="card">
                    <div class="alert alert-primary" style="margin-bottom: -1%">
                        <div>
                            <p style="font-size: 18px">
                                <i class='fas fa-exclamation' style="margin-right: 1%"></i> Recuerda que no es necesario
                                tener una cuenta institucional para poder poder enviar tu duda o requerimiento.
                            </p>
                            <p style="margin-top: -2%; text-align:center; font-size: 18px">
                                <strong>(*)</strong> campos obligatorios
                            </p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="author_name" class="col-md-4 col-form-label text-md-right">Nombre y Apellido
                                    <strong>(*)</strong></label>

                                <div class="col-md-6">
                                    <input id="author_name" type="text"
                                        class="form-control @error('author_name') is-invalid @enderror" name="author_name"
                                        value="{{ old('author_name') }}" required autocomplete="name" autofocus>

                                    @error('author_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="author_email" class="col-md-4 col-form-label text-md-right">Correo
                                    <strong>(*)</strong></label>

                                <div class="col-md-6">
                                    <input id="author_email" type="email"
                                        class="form-control @error('author_email') is-invalid @enderror" name="author_email"
                                        value="{{ old('author_email') }}" required autocomplete="email">

                                    @error('author_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="title"
                                    class="col-md-4 col-form-label text-md-right">@lang('cruds.ticket.fields.title')
                                    <strong>(*)</strong></label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title') }}" required autocomplete="title">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="content"
                                    class="col-md-4 col-form-label text-md-right">@lang('cruds.ticket.fields.content')
                                    <strong>(*)</strong></label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                        name="content" rows="3" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="attachments"
                                    class="col-md-4 col-form-label text-md-right">{{ trans('cruds.ticket.fields.attachments') }}</label>

                                <div class="col-md-6">
                                    <div style="border-color: #ced4da"
                                        class="needsclick dropzone @error('attachments') is-invalid @enderror"
                                        id="attachments-dropzone">
                                        <!--<div class="dz-message" data-dz-message><span>Arrastra tus imágenes aquí para cargarlas </span></div>!-->
                                    </div>
                                </div>
                                @error('attachments')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn"
                                        style="width: 100%; background-color: #e35000; color: white">
                                        <i class='fas fa-paper-plane' style="margin-right: 1%"></i> enviar
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

@section('scripts')
    <script>
        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('tickets.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            dictFallbackText: 'ggnomas',
            //dictDefaultMessage : null;
            dictDefaultMessage: "Arrastra tus imágenes aquí para cargarlas (máximo 2)",
            dictInvalidFileType: "Formato de Archivo no permitido",
            dictRemoveFile: "Quiero Eliminar este archivo",

            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedAttachmentsMap[file.name]
                }
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($ticket) && $ticket->attachments)
                    var files =
                    {!! json_encode($ticket->attachments) !!}
                    for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">')
                    }
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@stop