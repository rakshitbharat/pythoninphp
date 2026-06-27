# Concept: Python in PHP

## Overview
The `rakshitbharat/pythoninphp` package was initially conceived to solve a simple problem: running a Python script directly from a PHP application (like Laravel) and capturing its output.

Instead of writing complex API microservices or using heavy message queues, the package allowed developers to execute a Python script synchronously. It acted as a bridge, utilizing PHP's native execution functions (`exec` and `popen`) to invoke the Python interpreter natively on the host machine.

## How it worked (The Old Concept)
1. The developer wrote a Python script (e.g., `app/test.py`).
2. The developer called `Rakshitbharat\Pythoninphp\RunRun::thisFile('app/test.py')`.
3. The package resolved the path relative to the Laravel application's root directory (`base_path()`).
4. It detected the Operating System to locate the correct Python executable (`where python` for Windows, `which python` for Linux/macOS).
5. It executed the Python script using `popen()`, streamed the standard output/error, and returned it as a string to the PHP application.

## Rebirth
While the original concept was highly effective in its simplicity, modern applications demand robust error handling, security (argument escaping), and timeouts. The package has since been rewritten from the ground up to support modern PHP/Laravel architectures using `Symfony/Process`, Service Providers, and Facades.
