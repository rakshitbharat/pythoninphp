# API Reference (Legacy Version)

> [!WARNING]  
> This reference covers the original legacy classes. These have been completely replaced in modern versions.

## `Rakshitbharat\Pythoninphp\RunRun`

The primary static helper class.

### `thisFile(string $pythonFilePathLaravelFormat): string`
Instantiates a `PythonRunner` and executes the specified file.
- **`$pythonFilePathLaravelFormat`**: The path to the Python script relative to the Laravel application's `base_path()`.
- **Returns**: A string containing the captured output of the Python script.

---

## `Rakshitbharat\Pythoninphp\PythonRunner`

The underlying engine that executes the Python process.

### Properties
- `public string $file_path`: The absolute path to the Python script.
- `public string $out_put`: The captured output buffer.

### `pythonGrabber(string $pythonFilePathLaravelFormat): void`
Resolves and sets the absolute file path by prepending Laravel's `base_path()`.

### `run(): string`
Discovers the Python executable, runs the script via `popen`, captures the output stream (including errors), and returns it.
