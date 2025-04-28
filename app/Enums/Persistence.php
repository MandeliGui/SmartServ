<?php

declare(strict_types = 1);

namespace App\Enums;

enum Persistence: string
{
    case CREATE           = "CREATE";
    case UPDATE           = "UPDATE";
    case REMOVE           = "REMOVE";
    case CREATE_OR_UPDATE = "CREATE_OR_UPDATE";
    case FIND_ONE_BY_ID   = "FIND_ONE_BY_ID";
    case FIND_ALL         = "FIND_ALL";
    case REMOVE_MULTIPLE  = "REMOVE_MULTIPLE";
}
