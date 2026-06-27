---
name: pythoninphp-developer
description: >-
  A specialized skill for developing, maintaining, and debugging the pythoninphp
  Laravel package, including its Docker testing infrastructure and CI/CD pipelines.
---

# PythonInPHP Developer Skill

## Overview

**PythonInPHP** (`rakshitbharat/pythoninphp`) is a modern Laravel package that allows developers to execute Python scripts seamlessly from their PHP applications.

The package utilizes the `Symfony\Component\Process\Process` component to run Python scripts securely, handling timeouts, arguments, and standard error output efficiently.

**Packagist**: `https://packagist.org/packages/rakshitbharat/pythoninphp`
**GitHub**: `https://github.com/rakshitbharat/pythoninphp`

---

## Repository Structure

```
pythoninphp/
├── src/                                    # The Laravel package source
│   ├── PythonServiceProvider.php           # Registers bindings and publishes config
│   ├── PythonRunner.php                    # Core execution logic using Symfony Process
│   ├── Facades/
│   │   └── Python.php                      # Facade for clean syntax (Python::run())
│   └── Exceptions/
│       └── PythonExecutionException.php    # Custom exception for runtime errors
├── config/
│   └── pythoninphp.php                     # Configuration file (executable path, timeouts)
├── docs/                                   # Documentation
│   ├── concept.md                          # The original concept
│   ├── ARCHITECTURE.md                     # Legacy architecture reference
│   ├── USER_GUIDE.md                       # Legacy user guide
│   ├── API_REFERENCE.md                    # Legacy API reference
│   └── LARAVEL_INTEGRATION.md              # Modern usage instructions
├── tests/
│   ├── dummy.py                            # Python script for integration testing
│   └── PythonRunnerTest.php                # PHPUnit test cases
├── .github/
│   └── workflows/
│       ├── tests.yml                       # CI pipeline for running tests
│       └── release.yml                     # CD pipeline for auto-publishing to Packagist
├── Dockerfile                              # PHP 8.2 Alpine image for clean testing
├── docker-compose.yml                      # Docker compose setup to run the test suite
├── phpunit.xml.dist                        # PHPUnit configuration (v10+)
└── composer.json                           # Package metadata and dependencies
```

---

## Testing Environment (Docker)

To ensure the package runs correctly across different environments (especially when local PHP versions conflict, such as PHP 7.4 vs PHP 8.2+), we use Docker for local testing.

### Running Tests Locally

The `docker-compose.yml` file provisions a PHP 8.2 testing environment.

```bash
# Build the testing container and run composer install
docker compose run --rm test-runner composer install

# Run the test suite
docker compose run --rm test-runner vendor/bin/phpunit
```

---

## Critical Development Rules

1. **Modern PHP**: The package requires PHP `^8.2`. Do not introduce syntax or dependencies incompatible with this version.
2. **Laravel Support**: Supports `illuminate/support` `^10.0` and `^11.0`. Ensure backward/forward compatibility.
3. **Execution Security**: Always use `Symfony\Component\Process\Process`. Never use raw `exec()`, `shell_exec()`, or `popen()` which are vulnerable to command injection.
4. **Configuration**: Rely on the `config('pythoninphp.executable')` so developers can override the python binary path via their `.env` file (`PYTHON_EXECUTABLE`).
5. **No Direct Output Printing**: Always capture output and return it as a string, or throw a `PythonExecutionException` on failure. Do not `echo` or `dd()` inside the package code.
