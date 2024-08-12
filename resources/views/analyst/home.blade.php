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

@stop

@section('content')


@livewire('consulta.consulta')








@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Agregar el enlace al CSS de SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">

    <!-- Agregar el enlace al script de SweetAlert -->

@stop

@section('js')
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
            $('#grupo').select2();
            $('#linea').select2();
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
                        $('#rooms').append('<option value="' + room.CardCode + '">' + room
                            .CardCode + room
                            .CardName + '</option>');
                    });
                    $('#rooms').select2();
                });
            });
            $('#grupo').on('change', function(e) {
                var selectedValue = $(this).val();
                var url = '/get-lineas/' +
                    selectedValue; // This should match the route defined in routes/web.php

                $.get(url, function(response) {
                    var valor = response.valor;
                    console.log(valor);
                    $('#linea').empty().append(
                        '<option value="" selected disabled>Seleccione Linea</option>');
                    response.lineas.forEach(function(linea) {
                        $('#linea').append('<option value="' + linea.U_codlinea + '">' +
                            linea
                            .U_linea + '</option>');
                    });
                    $('#linea').select2();
                });
            });
            $('#linea').on('change', function(e) {
                var selectedValue = $(this).val();
                var url = '/get-productos/' +
                    selectedValue; // Update the URL to match your backend endpoint

                $.get(url, function(response) {
                    var valor = response.valor;
                    console.log(valor);
                    $('#producto').empty().append(
                        '<option value="" selected disabled>Seleccione Producto</option>');
                    response.productos.forEach(function(producto) {
                        $('#producto').append('<option value="' + producto.ItemCode + '">' +
                            producto.ItemName + '</option>');
                    });
                    $('#producto').select2();
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
                        text: 'Por favor, complete todos los campos requeridos.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                var formData = $(this).serialize(); // Serialize form data
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
                            title: 'Datos Registrados', // Cambiar el título
                            text: 'Todos los campos fueron registrados.', // Cambiar el texto
                            confirmButtonText: 'Aceptar'
                        });
                        $('#producto').val('');
                        $('#cantidad').val('');
                        $('#fecha_vencimiento').val('');

                        $('#producto').val(null).trigger('change');
                        console.log('Data saved successfully:', response);
                    },
                    error: function(error) {
                        // Handle error response here, such as showing an error message
                        console.error('Error while saving data:', error);
                    }
                });
            });

        });

        // Resto de tu código aquí...ƒ∂ƒ
    </script>
@stop
