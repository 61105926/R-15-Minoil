@extends('adminlte::page')

@section('title', 'Dashboard')
<meta charset="UTF-8">

@section('content_header')
    <style>
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        img.responsive-image {
            max-width: 30%;
            /* Puedes ajustar el valor para controlar el tamaño */
            height: auto;
        }
    </style>

    <div class="image-container">
        <img src="https://www.minoil.com.bo/wp-content/uploads/2019/04/logo-minoil1.png" alt="Descripción de la imagen"
            class="responsive-image">
    </div>
    @livewireStyles

@stop

@section('content')
    <div class="container mt-1">
        <h5 class="mt-0">Sala</h5>
        <form class="needs-validation" novalidate>
            @csrf
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="chain">Cadena</label>
                    <select class="custom-select" name="cadena" id="chain" required>
                        <option selected disabled>Seleccione la cadena</option>
                        @foreach ($chains as $chain)
                            <option value="{{ $chain->GlblLocNum }}">{{ $chain->GlblLocNum }}</option>
                        @endforeach
                    </select>
                    <div class="valid-feedback">
                        ¡Excelente elección!
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="rooms">Sala</label>
                    <select class="custom-select select2-search" name="sala" id="rooms" required>
                        <option selected disabled>Seleccione la sala</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->CardCode }}">{{ $room->CardName - $room->AliasName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ... -->
            <h5 class="mt-0">Producto</h5>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="producto">Producto</label>
                    <select class="custom-select" name="producto" required id="producto">
                        <option selected disabled>Seleccione Producto</option>
                        @if (count($productos) > 0)
                            @foreach ($productos as $product)
                                <option value="{{ $product->ItemCode }}">

                                    {{ $product->CodeBars }}-{{ $product->ItemName }}
                                </option>
                            @endforeach
                        @else
                            <option disabled>No hay productos disponibles</option>
                        @endif
                    </select>

                    {{-- <p>Cantidad de productos: {{ count($productos) }}</p> --}}
                </div>
                <div class="col-md-6 mb-3">
                    <?php foreach ($censo as $item): ?>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" id="check<?= $item->Id ?>" name="checkCotizaciones"
                            value="<?= $item->Id ?>" required>
                        <label class="form-check-label" for="check<?= $item->Id ?>"><?= $item->Descripcion ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- ... -->
            <h5 class="mt-0">Censo</h5>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_vencimiento">Fecha de vencimiento</label>
                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento"
                        placeholder="Zip" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad"
                        required>
                </div>
                <div class="col-12 d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>





@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Agregar el enlace al CSS de SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">

    <!-- Agregar el enlace al script de SweetAlert -->

@stop

@section('js')
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- Enlace al archivo JavaScript de Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Enlace al archivo JavaScript de select2-cjk-compatibility -->
    <script>
        $(document).ready(function() {
            $('#chain').select2();
            $('#rooms').select2();
            $('#producto').select2();
            $('#chain').on('change', function(e) {
                var selectedValue = $(this).val();
                console.log(selectedValue);

                var url = '/get-rooms/' + selectedValue;
                $.get(url, function(response) {
                    var valor = response.valor;
                    console.log(valor);
                    $('#rooms').empty().append(
                        '<option value="" selected disabled>Seleccione la sala</option>');
                    response.rooms.forEach(function(room) {
                        console.log(room);

                        $('#rooms').append('<option value="' + room.CardCode + '">' + room
                            .CardName + '-' + room
                            .AliasName + '</option>');
                    });
                    $('#rooms').select2();
                });
            });

            $('form.needs-validation').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission behavior

                var allFieldsFilled = true;

                $(this).find('input, select').each(function() {
                    if ($(this).prop('required') && !$(this).val()) {
                        allFieldsFilled = false;
                        return false; // Salir del bucle al encontrar un campo vacío
                    }
                });

                if (!allFieldsFilled) {
                    // Mostrar alerta SweetAlert de validación
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos Incompletos',
                        text: 'Por favor, complete todos los campos requeridos',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }


                var formData = $(this).serialize(); // Serialize form data
                var checkboxValue = $('input[name="checkCotizaciones"]:checked').val();
                formData += '&checkCotizaciones=' + checkboxValue;

                var url = '/save-data'; // Update this to match your backend endpoint
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        // Handle success response here, such as showing a success message
                        Swal.fire({
                            icon: 'success', // Cambiar a 'success'
                            title: response.message, // Cambiar el título
                            text: 'Todos los campos fueron registrados.', // Cambiar el texto
                            confirmButtonText: 'Aceptar',
                            timer: 3000, // Duración en milisegundos (en este caso, 3 segundos)
                            timerProgressBar: true, // Barra de progreso durante el tiempo
                            showConfirmButton: true // Oculta el botón de confirmación
                        });
                        $('#producto').val('');
                        $('#cantidad').val('');
                        $('#fecha_vencimiento').val('');
                        $('input[name="checkCotizaciones"]').prop('checked', false);

                        $('#producto').val(null).trigger('change');
                        console.log('Data saved successfully:', response);
                    },
                    error: function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error while saving data:', error);

                    // Extract error message from response
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Ha ocurrido un error inesperado.';

                    // Show SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonText: 'Aceptar'
                    });
                }
                });
            });

        });

        // Resto de tu código aquí...ƒ∂ƒ
    </script>
@stop
