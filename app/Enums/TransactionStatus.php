<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case SUCCESS = 'sucesso';
    case WAITING = 'aguardando';
    case FAILED = 'falhou';
}
