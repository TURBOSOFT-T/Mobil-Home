<div>

    

    @include('components.alert')

    <div class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark cusor">
                <tr>
                   
                    <th>Image</th>
                    <th>Nom service</th>
                    <th>Description</th>
                   
                    <th> Date création</th>
                    <th style="text-align: right;">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow"
                                alt="">
                        </span>
                    </th>
                </tr>
            </thead>


            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td>
                            <img src="{{ Storage::url($service->image) }}" width="40 " height="40 "
                                class="rounded shadow" alt="">
                               <!--  <img src="{{ asset('storage/'.$service->photo) }}" alt="..."> -->
                        </td>
                        <td>
                            

                            {{ $service->nom }}
                        </td>

                        <td>
                            

                            {{ $service->description }}
                        </td>
                       
                        
                       
                       
                        <td>{{ $service->created_at->format('d/m/Y') }} </td>
                        <td style="text-align: right;">
                            <div class="btn-group">
                                
                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#service-{{ $service->id }}">
                                <i class="ri-edit-box-line"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="toggle_confirmation({{ $service->id }})">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                            <button class="btn btn-sm btn-success d-none" type="button" id="confirmBtn{{ $service->id }}"
                                wire:click="delete({{ $service->id }})">
                                <i class="bi bi-check-circle"></i>
                                <span class="hide-tablete">
                                    Confirmer
                                </span>
                            </button>
                            </div>

                          
                            <button class="btn btn-sm btn-success d-none" type="button"
                                id="confirmBtn{{ $service->id }}" wire:click="delete({{ $service->id }})">
                                <i class="bi bi-check-circle"></i>
                                <span class="hide-tablete">
                                    Confirmer
                                </span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            <div>
                                <img src="/icons/icons8-ticket-100.png" height="100" width="100" alt=""
                                    srcset="">
                            </div>
                            Aucun service trouvé
                        </td>
                    </tr>
                @endforelse

            </tbody>


        </table>

        
    @foreach ($services as $service)
    <!-- Center modal content -->
    <div class="modal fade" id="service-{{ $service->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        {{ $service->nom }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                       {{--  @livewire('Marques.Update',['marque'=>$marque]) --}}
                       @livewire('Services.UpdateService', ['service'=>$service])
                    </p>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endforeach
    </div>
    {{ $services->links('pagination::bootstrap-4') }}



</div>
