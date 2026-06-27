<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Python Executable Path
    |--------------------------------------------------------------------------
    |
    | Define the path to the Python executable. By default, the package will
    | attempt to guess it based on the operating system, but you can override
    | it here if your environment requires a specific path.
    |
    */
    'executable' => env('PYTHON_EXECUTABLE', null),

    /*
    |--------------------------------------------------------------------------
    | Process Timeout
    |--------------------------------------------------------------------------
    |
    | The default timeout in seconds for running Python scripts. Set to null
    | for no timeout.
    |
    */
    'timeout' => env('PYTHON_TIMEOUT', 60),
];
