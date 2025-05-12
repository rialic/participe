<x-mail::layout>
<x-slot:header>
    <x-mail::header :url="$url">
        <img width="120" height="120" src="{{ url('storage/logo-telessaude.png') }}" alt="logo-telessaude"/>
    </x-mail::header>
</x-slot:header>
Olá {{ $name }},

Esse é o seu email com o link mágico para acessar o sistema participe, clique no botão abaixo para entrar no sistema.

<x-mail::button :url="$url">
    <span style="font-weight: bold;">Entrar no participe</span>
</x-mail::button>

Caso o botão acima não funcione, copie e cole o link mágico na url do seu navegador:

<a href="{{ $url }}">{{ $url }}</a>

Atenciosamente,

Equipe Telessáude Mato Grosso do Sul.
<x-slot:footer>
<x-mail::footer>

</x-mail::footer>
</x-slot:footer>
</x-mail::layout>