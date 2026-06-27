# Laravel Integration Guide

## Introduction

The modern `pythoninphp` package is designed to integrate seamlessly into Laravel 10 and 11 applications. It uses Laravel's Service Container, Facades, and config system to provide a first-class developer experience.

## Installation

Install the package via Composer:

```bash
composer require rakshitbharat/pythoninphp
```

Laravel's package auto-discovery will automatically register the `PythonServiceProvider` and the `Python` facade.

## Configuration

Optionally, you can publish the configuration file to customize the Python executable path and default timeouts:

```bash
php artisan vendor:publish --tag="pythoninphp-config"
```

This will create a `config/pythoninphp.php` file. You can then override the settings in your `.env` file:

```env
PYTHON_EXECUTABLE=/usr/bin/python3
PYTHON_TIMEOUT=120
```

## Basic Usage

You can use the `Python` facade to execute scripts easily.

### 1. Simple Execution
```php
use Rakshitbharat\Pythoninphp\Facades\Python;

// Path is relative to your Laravel application's root directory (base_path())
$output = Python::run('app/Scripts/hello.py');
```

### 2. Passing Arguments
Pass an array of arguments as the second parameter. They are safely escaped by the underlying Symfony Process component.

```php
$output = Python::run('app/Scripts/process_data.py', [
    '--file=input.csv',
    '--verbose'
]);
```

### 3. Exception Handling
If the script fails (non-zero exit code) or times out, a `PythonExecutionException` is thrown.

```php
use Rakshitbharat\Pythoninphp\Exceptions\PythonExecutionException;

try {
    $output = Python::run('app/Scripts/unreliable.py');
} catch (PythonExecutionException $e) {
    // Access the error output or message
    report($e);
    echo "Python script failed: " . $e->getMessage();
}
```
