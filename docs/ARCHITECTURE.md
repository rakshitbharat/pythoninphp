# Architecture (Legacy Version)

## System Structure
In the original iteration of `pythoninphp`, the architecture was extremely lightweight and consisted of just two main classes:

1.  **`RunRun.php`**: The entry point. It provided a static helper method (`thisFile`) to instantiate the runner and execute it in a single line.
2.  **`PythonRunner.php`**: The core execution engine.

## Execution Flow

1. **Path Resolution**: 
   The `PythonRunner` took a relative path and appended it to Laravel's `base_path()`.
   
2. **Interpreter Discovery**:
   It dynamically checked the OS using `PHP_OS`.
   - On Windows: Ran `exec("where python")`
   - On Unix: Ran `exec("which python")`

3. **Process Execution via `popen`**:
   It constructed a raw command string: `$pythonPath . " " . $this->file_path . " 2>&1"`.
   The `2>&1` ensured that Standard Error (stderr) was piped into Standard Output (stdout), allowing PHP to capture errors (like Python Tracebacks) in the same stream.

4. **Stream Reading**:
   It opened the process using `popen($command, "r")` and looped using `feof()` and `fread()` to capture the output into a string. It utilized `usleep(100000)` to prevent the while-loop from maxing out CPU usage while waiting for the Python script to finish executing.

## Limitations (Why it was rewritten)
- **Security**: Building command strings manually via concatenation is vulnerable to command injection if filenames or arguments are dynamic.
- **Timeouts**: The `while(!feof())` loop had no maximum execution time, meaning a hanging Python script would hang the PHP process indefinitely.
- **Modern Standards**: It lacked proper Exceptions, configurations, and deep Laravel integration (Facades, Service Providers).
