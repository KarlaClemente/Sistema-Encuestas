@props([
    'estilo' => '',
])

<div class="card h-100" id="estilo-{{ $estilo }}" onclick="selectEstilo('{{ $estilo }}')">
    <img src="{{ asset('images/estilos/' . ucfirst($estilo) . '.png') }}" 
        class="card-img-top img-fluid" 
        alt="Estilo {{ $estilo }}"
        style="object-fit: cover; height: 150px;">
    <div class="card-body">
        <h5 class="card-title">{{ $estilo }}</h5>
    </div>
</div>