<?php

namespace App\Enums;

enum SettingType: string
{
    case Boolean = 'boolean';
    case Text = 'text';
    case Integer = 'integer';
    case Json = 'json';
}
