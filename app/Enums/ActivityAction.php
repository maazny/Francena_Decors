<?php

namespace App\Enums;

enum ActivityAction: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case FORCE_DELETE = 'force_delete';
    case UPLOAD = 'upload';
    case DOWNLOAD = 'download';
    case EXPORT = 'export';
    case IMPORT = 'import';
    case PUBLISH = 'publish';
    case UNPUBLISH = 'unpublish';
    case APPROVE = 'approve';
    case REJECT = 'reject';
    case SETTINGS_UPDATE = 'settings_update';
    case PERMISSION_CHANGE = 'permission_change';
    case ROLE_CHANGE = 'role_change';
    case BACKUP = 'backup';
    case CACHE_CLEAR = 'cache_clear';
    case MEDIA_UPLOAD = 'media_upload';
    case MEDIA_DELETE = 'media_delete';
    case THEME_UPDATE = 'theme_update';
    case SEO_UPDATE = 'seo_update';
    case NEWSLETTER_EXPORT = 'newsletter_export';
    case CONTACT_REPLY = 'contact_reply';
}
