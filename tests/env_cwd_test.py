import os
import sys

if __name__ == "__main__":
    # Print working directory
    print(f"CWD: {os.getcwd()}")
    
    # Print custom env variable
    custom_var = os.environ.get("MY_CUSTOM_VAR")
    if custom_var:
        print(f"ENV_VAR: {custom_var}")

    # If failure argument passed, fail
    if len(sys.argv) > 1 and sys.argv[1] == "fail":
        print("Standard Output before crash")
        sys.stderr.write("Error Output during crash\n")
        sys.exit(1)
