<?php
namespace App\Enums;

enum RelationType : string
{
    case FATHER = 'Father';
    case MOTHER = 'Mother';
    case BROTHER = 'Brother';
    case SISTER = 'Sister';

    public static function getValues(): array
    {
        return array_column(RelationType::cases(),'value');
    }

} 