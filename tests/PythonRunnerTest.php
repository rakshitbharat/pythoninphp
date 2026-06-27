<?php

namespace Rakshitbharat\Pythoninphp\Tests;

use Orchestra\Testbench\TestCase;
use Rakshitbharat\Pythoninphp\PythonServiceProvider;
use Rakshitbharat\Pythoninphp\Facades\Python;
use Rakshitbharat\Pythoninphp\Exceptions\PythonExecutionException;

class PythonRunnerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PythonServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Python' => Python::class,
        ];
    }

    public function test_it_runs_python_script_without_args()
    {
        $output = Python::run(__DIR__ . '/dummy.py');
        $this->assertStringContainsString('Hello from Python!', $output);
    }

    public function test_it_runs_python_script_with_args()
    {
        $output = Python::run(__DIR__ . '/dummy.py', ['foo', 'bar']);
        $this->assertStringContainsString('Hello from Python! Args: foo, bar', $output);
    }

    public function test_it_throws_exception_if_script_does_not_exist()
    {
        $this->expectException(PythonExecutionException::class);
        $this->expectExceptionMessage('Python script not found');
        
        Python::run(__DIR__ . '/missing.py');
    }
}
