<div>
    <div class="container mt-1">
        <h5 class="mt-0">Sala</h5>

        <div class="form-row">
            <div class="col-md-6 mb-3">
                {{-- {{ $cadena }} --}}
                <label for="chain">Cadena</label>
                <select class="custom-select" wire:model='chain_id' wire:change='cadena' required>
                    <option selected >Seleccione la cadena</option>
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
                <select class="custom-select select2-search" wire:model='room_id' name="sala" required>
                    <option selected >Seleccione la sala</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->CardCode }}">{{ $room->CardName }}-{{ $room->AliasName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <button wire:click="mostrarDatos" class="btn btn-primary">Mostrar</button>
        </div>


        @if ($datosInsertados && count($datosInsertados) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Codigo Producto</th>
                            <th>Nombre Producto</th>
                            <th>Sala</th>
                            <th>Fecha Vencimiento</th>
                            <th>Stock</th>
                            <th>Censo</th>
                            <th>Fecha-Subida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosInsertados as $dato)
                            <tr>
                                <td>{{ $dato->ItemCode }}</td>
                                <td>{{ $dato->ItemName }}</td>
                                <td>{{ $dato->CardName }}</td>
                                <td>{{ $dato->FV }}</td>
                                <td>{{ $dato->Stock }}</td>
                                <td>
                                    @if ($dato->IdCenso == 1)
                                        <span class="badge badge-primary">Nuevo</span>
                                    @elseif ($dato->IdCenso == 2)
                                        <span class="badge badge-secondary">Bandeo</span>
                                    @elseif ($dato->IdCenso == 3)
                                        <span class="badge badge-success">2x1</span>
                                    @else
                                        {{ $dato->IdCenso }} <!-- Manejar otro valor de IdCenso -->
                                    @endif
                                </td>                                <td>{{ $dato->CreateDate }}</td>
                                <td>
                                    <a wire:click="confirmDelete('{{ $dato->CardCode }}', '{{ $dato->ItemCode }}', '{{ $dato->FV }}', '{{ $dato->Stock }}', '{{ $dato->IdCenso }}', '{{ $dato->CreateDate }}')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                    {{-- <a onclick="confirmDelete('{{ $dato->CardCode }}', '{{ $dato->ItemCode }}', '{{ $dato->FV }}', '{{ $dato->Stock }}', '{{ $dato->IdCenso }}', '{{ $dato->CreateDate }}')">
                                        delete
                                    </a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay datos disponibles.</p>
        @endif
    </div>
    {{-- @livewireScripts --}}
    @script
        <script>
            
            $wire.on('showConfirmation', (parameters) => {
                console.log(parameters['CardCode']);
                //
                Swal.fire({
                    title: '¿Estás seguro de que deseas eliminar ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch('deleteConfirmed',parameters);
                    }
                });
            });
        </script>
    @endscript



</div>
