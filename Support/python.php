<?php

function pythonRunner($pythonFilePathLaravelFormat)
{
    $PythonRunner = new \App\Python\PythonRunner();
    $PythonRunner->pythonGrabber($pythonFilePathLaravelFormat);
    return $PythonRunner->run();
}