<div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <label for="mes">Sede:</label>
                <label for="mes">{{ $sede }}</label>
                <select wire:model.live="sede" class="form-control">
                    <option selected>Seleccione Sede</option>
                    @foreach ($sucursal as $sucursa)
                        <option value="{{ $sucursa->Code }}">{{ $sucursa->Name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="mes">Seleccione Categoria:</label>
                <select wire:model="categoria" class="form-control">
                    <option selected>Seleccione Grupo</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->U_JefeMarca }}">{{ $group->U_JefeMarca }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="month">Selecciona el mes:</label>

                <select wire:model="month" class="form-control">
                    <!-- Opciones del mes aquí -->
                    <option selected>Seleccione Mes</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="year">Selecciona el año:</label>
                <select wire:model="year" class="form-control">
                    <!-- Opciones del año aquí -->
                    <option selected>Seleccione año</option>

                    <?php
                    $anoActual = date('Y');
                    for ($i = $anoActual; $i >= $anoActual - 10; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="ano">Mostrar datos:</label>
                <button wire:click="mostrarDatos" class="btn btn-primary">Mostrar</button>
            </div>
        </div>
      
    </div>

    <div class="tabla-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>ItemCode</th>
                    @foreach ($encabezadosSala as $sala)
                        <th>{{ $sala['nombre'] }}-{{ $sala['AliasName'] }}</th>
                    @endforeach
                    <th>FV</th>
                    <th>Total</th>
                    <th>Acciones</th>
                    <th>Cargo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pivotData as $producto => $fechasData)
                    @foreach ($fechasData as $fecha => $salaData)
                        <tr>
                            <td>{{ $productoNombres[$producto] ?? '' }}</td> <!-- Mostramos el nombre del producto -->
                            <td>{{ $producto }}</td>

                            @foreach ($encabezadosSala as $sala)
                                <td>{{ $salaData[$sala['codigo']] ?? 0 }}</td>
                            @endforeach
                            <td>{{ $fecha }}</td>
                            <td>{{ array_sum($salaData) }}</td> <!-- Sumar los valores de la fila -->
                            <td>
                                <div class="container mt-4">
                                    <div>
                                        <input type="checkbox"
                                            wire:model.live="showText.{{ $producto }}.{{ $fecha }}">
                                        Mostrar
                                        Texto

                                        @if (!data_get($showText, "$producto.$fecha", false))
                                            <label for="miSelect">Selecciona una opción:</label>
                                            <select
                                                wire:model="selectedAcciones.{{ $producto }}.{{ $fecha }}"
                                                wire:change="actualizarDatos('{{ $producto }}', '{{ $fecha }}')"
                                                class="form-control">
                                                <option value="" selected>Selecciona una opción</option>
                                                @foreach ($acciones as $accion)
                                                    <option value="{{ $accion->Id }}">{{ $accion->Accion }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text"
                                                wire:model="textoPersonalizado.{{ $producto }}.{{ $fecha }}"
                                                placeholder="Texto personalizado">
                                            <button
                                                wire:click="enviarTexto('{{ $producto }}', '{{ $fecha }}')">Enviar</button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                            <label for="miSelect">Selecciona tipo de cargo:</label>

                                <select wire:model="selectedAporte.{{ $producto }}.{{ $fecha }}"
                                    wire:change="actualizarDatosAporte('{{ $producto }}', '{{ $fecha }}')"
                                    class="form-control">
                                    <option value="" selected>Selecciona una opción</option>
                                    @foreach ($aportes as $aporte)
                                        <option value="{{ $aporte->Id }}">{{ $aporte->Aporte }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@script
    <script>
        $wire.on('datos-actualizados', datos => {
            //

            Swal.fire('Éxito', 'La actualización se realizó con éxito', 'success');


        });
    </script>
@endscript
