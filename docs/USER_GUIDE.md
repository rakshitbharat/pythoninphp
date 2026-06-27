# User Guide (Legacy Version)

> [!WARNING]  
> This guide is for historical reference. The syntax described below belongs to the original legacy version of the package and is no longer supported in modern releases.

## Legacy Usage

The legacy package was designed to be as simple as possible. It provided a single static method to run a Python file.

```php
use Rakshitbharat\Pythoninphp\RunRun;

// Run a Python script located relative to the Laravel project root
$output = RunRun::thisFile('app/scripts/hello.py');

echo $output;
```

### Steps:
1. Ensure Python was installed and available in your system's PATH.
2. Place a Python script somewhere in your Laravel project (e.g., `app/test.py`).
3. Call the `RunRun::thisFile()` method passing the relative path to the script.
4. The method would block execution until the Python script finished, returning the standard output (and standard error) as a string.
