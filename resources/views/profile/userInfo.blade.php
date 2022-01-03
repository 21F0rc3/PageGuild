@extends('profile.profileView')

@section('optionContent')
    <!-- Informações do Utilizador -->
    <form method="POST" action="{{ route('updateInfo') }}">
    @csrf
    @method('POST')
        <div class="row bg-white my-4 p-5">
            <!-- Nome -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="name" id="floatingInput" placeholder="Nome" value="{{ $user->name }}">
                <label for="floatingInput">Nome</label>
            </div>
            <!-- Email -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email', 'userInfo') is-invalid @enderror" name="email" id="floatingInput" placeholder="name@example.com" value="{{ $user->email }}">
                <label for="floatingInput">E-mail</label>

                <!-- em caso de erro --> 
                @error('email', 'userInfo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- NIF -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nif" id="floatingInput" placeholder="NIF" value="{{ $user->nif }}">
                <label for="floatingInput">NIF</label>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </form>

    <!-- Enderecos do utilizador -->
    <form method="POST"  action="{{ route('updateInfo') }}">
    @csrf
    @method('POST')
        <div class="row bg-white my-4 p-5">  
            <h2>ENDERECOS ATIVOS</h2>
            @if($activeAddress->isEmpty())
                <h8>Não possuis nenhum endereço ativo</h8> 
            @endif

            @isset($activeAddress)
                @foreach($activeAddress as $address)
                    <h8>{{ $address->address }}</h8> 
                @endforeach
            @endisset
        
            <h2>ENDERECOS DESATIVADOS</h2>
            @if($deactiveAddress->isEmpty())
                <h8>Não possuis nenhum endereço desativo</h8> 
            @endif

            @isset($deactiveAddress)
                @foreach($deactiveAddress as $address)
                    <h8>{{ $address->address }}</h8> 
                @endforeach
            @endisset
        </div>
    </form>
@endsection