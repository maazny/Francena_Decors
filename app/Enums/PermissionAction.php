<?php

namespace App\Enums;

enum PermissionAction: string
{
    case VIEW = 'view';
    case CREATE = 'create';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case PUBLISH = 'publish';
    case EXPORT = 'export';
    case IMPORT = 'import';
    case ASSIGN = 'assign';
    case CONFIGURE = 'configure';
}
