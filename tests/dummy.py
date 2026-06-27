import sys

if __name__ == "__main__":
    if len(sys.argv) > 1:
        print(f"Hello from Python! Args: {', '.join(sys.argv[1:])}")
    else:
        print("Hello from Python!")
