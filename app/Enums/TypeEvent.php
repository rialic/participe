<?php

namespace App\Enums;

enum TypeEvent: string
{
    case Curso = 'Curso';
    case WebaulasPalestras = 'Webaulas/palestras';
    case Webseminarios = 'Webseminários';
    case ForumDiscussão = 'Fórum de discussão';
    case ReuniaoMatriciamento = 'Reunião de matriciamento';
}
