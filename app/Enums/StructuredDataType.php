<?php

namespace App\Enums;

enum StructuredDataType: string
{
    case ORGANIZATION = 'organization';
    case LOCAL_BUSINESS = 'local_business';
    case WEBSITE = 'website';
    case WEBPAGE = 'webpage';
    case BREADCRUMB = 'breadcrumb';
    case ARTICLE = 'article';
    case FAQ = 'faq';
    case SERVICE = 'service';
    case CONTACT = 'contact';
    case GALLERY = 'gallery';
    case PERSON = 'person';
    case CUSTOM = 'custom';
}
