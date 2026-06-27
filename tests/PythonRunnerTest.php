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

    public function test_it_runs_with_custom_working_directory()
    {
        $output = Python::withWorkingDirectory('/')->run(__DIR__ . '/env_cwd_test.py');
        $this->assertStringContainsString('CWD: /', $output);
    }

    public function test_it_runs_with_custom_environment_variables()
    {
        $output = Python::withEnv(['MY_CUSTOM_VAR' => 'Antigravity'])->run(__DIR__ . '/env_cwd_test.py');
        $this->assertStringContainsString('ENV_VAR: Antigravity', $output);
    }

    public function test_it_throws_detailed_exception_on_failure()
    {
        try {
            Python::run(__DIR__ . '/env_cwd_test.py', ['fail']);
            $this->fail('Expected PythonExecutionException was not thrown.');
        } catch (PythonExecutionException $e) {
            $this->assertStringContainsString('Python script execution failed with exit code 1.', $e->getMessage());
            $this->assertStringContainsString('Error Output:', $e->getMessage());
            $this->assertStringContainsString('Error Output during crash', $e->getMessage());
            $this->assertStringContainsString('Standard Output:', $e->getMessage());
            $this->assertStringContainsString('Standard Output before crash', $e->getMessage());
        }
    }

    public function test_it_can_be_configured_fluently_without_mutating_singleton()
    {
        $original = Python::getFacadeRoot();
        
        // This should not affect the singleton instance
        $configured = $original->withEnv(['MY_CUSTOM_VAR' => 'Antigravity']);
        
        // Run with the original instance -> should NOT output "ENV_VAR: Antigravity"
        $outputFromOriginal = $original->run(__DIR__ . '/env_cwd_test.py');
        $this->assertStringNotContainsString('ENV_VAR: Antigravity', $outputFromOriginal);
        
        // Run with the configured instance -> should output "ENV_VAR: Antigravity"
        $outputFromConfigured = $configured->run(__DIR__ . '/env_cwd_test.py');
        $this->assertStringContainsString('ENV_VAR: Antigravity', $outputFromConfigured);
    }
}
