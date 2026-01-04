<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static chunk(int $int, \Closure $param)
 */
abstract class SitemapableModel extends Model
{
    public function getViewRouteName(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $className = Str::plural($className);

        return strtolower($className) . '.show';
    }

    public function getRouteParameters(): array
    {
        return [$this];
    }

    public function getSitemapRoute(bool $absolute = true): string
    {
        return route($this->getViewRouteName(), $this->getRouteParameters(), $absolute);
    }
}
