<?php

namespace Rakshitbharat\Pythoninphp;

use Rakshitbharat\Pythoninphp\Exceptions\PythonExecutionException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class PythonRunner
{
    /**
     * The path to the Python executable.
     *
     * @var string
     */
    protected string $executable;

    /**
     * The default timeout in seconds.
     *
     * @var int|float|null
     */
    protected $timeout;

    /**
     * Create a new PythonRunner instance.
     *
     * @param string $executable
     * @param int|float|null $timeout
     */
    public function __construct(string $executable = 'python3', $timeout = 60)
    {
        $this->executable = $executable;
        $this->timeout = $timeout;
    }

    /**
     * Execute a Python script.
     *
     * @param string $scriptPath The relative path from the application base path.
     * @param array $arguments Additional arguments to pass to the script.
     * @return string The output from the script.
     *
     * @throws \Rakshitbharat\Pythoninphp\Exceptions\PythonExecutionException
     */
    public function run(string $scriptPath, array $arguments = []): string
    {
        if (function_exists('base_path')) {
            $absolutePath = base_path($scriptPath);
        } else {
            // Fallback for non-Laravel usage
            $absolutePath = $scriptPath;
        }

        if (! file_exists($absolutePath)) {
            throw new PythonExecutionException("Python script not found at path: {$absolutePath}");
        }

        $command = array_merge([$this->executable, $absolutePath], $arguments);

        $process = new Process($command);
        $process->setTimeout($this->timeout);

        try {
            $process->mustRun();
            return $process->getOutput();
        } catch (ProcessFailedException $e) {
            throw new PythonExecutionException(
                "Python script execution failed:\n" . $e->getProcess()->getErrorOutput(),
                $e->getCode(),
                $e
            );
        } catch (ProcessTimedOutException $e) {
            throw new PythonExecutionException(
                "Python script execution timed out after {$this->timeout} seconds.",
                $e->getCode(),
                $e
            );
        }
    }
}
