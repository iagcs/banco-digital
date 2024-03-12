<?php

namespace App\Enums;

enum FinanceOperationStatus: string
{
    case SUCCESS = 'sucesso';
    case WAITING = 'aguardando';
    case FAILED = 'falhou';
}
