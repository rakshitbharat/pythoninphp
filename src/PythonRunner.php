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
     * The working directory for the process.
     *
     * @var string|null
     */
    protected ?string $workingDirectory = null;

    /**
     * Additional environment variables for the process.
     *
     * @var array
     */
    protected array $env = [];

    /**
     * Create a new PythonRunner instance.
     *
     * @param string|null $executable
     * @param int|float|null $timeout
     */
    public function __construct(?string $executable = null, $timeout = 60)
    {
        $this->executable = $executable ?? $this->getDefaultExecutable();
        $this->timeout = $timeout;
    }

    /**
     * Get the default Python executable based on the operating system.
     *
     * @return string
     */
    protected function getDefaultExecutable(): string
    {
        return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'python' : 'python3';
    }

    /**
     * Set a custom timeout for the process (fluent).
     *
     * @param int|float|null $timeout
     * @return self
     */
    public function withTimeout($timeout): self
    {
        $clone = clone $this;
        $clone->timeout = $timeout;
        return $clone;
    }

    /**
     * Set the working directory for the process (fluent).
     *
     * @param string $cwd
     * @return self
     */
    public function withWorkingDirectory(string $cwd): self
    {
        $clone = clone $this;
        $clone->workingDirectory = $cwd;
        return $clone;
    }

    /**
     * Set environment variables for the process (fluent).
     *
     * @param array $env
     * @return self
     */
    public function withEnv(array $env): self
    {
        $clone = clone $this;
        $clone->env = array_merge($this->env, $env);
        return $clone;
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
        $absolutePath = $scriptPath;

        if (!$this->isAbsolutePath($scriptPath) && function_exists('base_path')) {
            $absolutePath = base_path($scriptPath);
        }

        if (! file_exists($absolutePath)) {
            throw new PythonExecutionException("Python script not found at path: {$absolutePath}");
        }

        $command = array_merge([$this->executable, $absolutePath], $arguments);

        $process = new Process($command);
        $process->setTimeout($this->timeout);

        if ($this->workingDirectory !== null) {
            $process->setWorkingDirectory($this->workingDirectory);
        }

        if (! empty($this->env)) {
            $process->setEnv($this->env);
        }

        try {
            $process->mustRun();
            return $process->getOutput();
        } catch (ProcessFailedException $e) {
            $failedProcess = $e->getProcess();
            $errorOutput = $failedProcess->getErrorOutput();
            $output = $failedProcess->getOutput();

            $message = "Python script execution failed with exit code {$failedProcess->getExitCode()}.\n";
            if (! empty($errorOutput)) {
                $message .= "Error Output:\n" . $errorOutput;
            }
            if (! empty($output)) {
                $message .= "Standard Output:\n" . $output;
            }

            throw new PythonExecutionException(
                $message,
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

    /**
     * Check if the given path is absolute.
     *
     * @param string $path
     * @return bool
     */
    protected function isAbsolutePath(string $path): bool
    {
        if (strspn($path, '/\\', 0, 1)) {
            return true;
        }

        if (strlen($path) > 3 && ctype_alpha($path[0]) && $path[1] === ':' && strspn($path, '/\\', 2, 1)) {
            return true;
        }

        return false;
    }
}
