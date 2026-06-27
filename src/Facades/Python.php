<?php

namespace Rakshitbharat\Pythoninphp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string run(string $scriptPath, array $arguments = [])
 *
 * @see \Rakshitbharat\Pythoninphp\PythonRunner
 */
class Python extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pythoninphp';
    }
}
