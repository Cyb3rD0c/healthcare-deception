import os
import json
import time

log_directory = "/var/log/myapp"
output_json_file = os.path.join(log_directory, "consolidated_logs.json")
rotation_size = 10 * 1024 * 1024  # 10 MB
max_files = 5

# Function to rotate logs
def rotate_logs():
    if os.path.exists(output_json_file) and os.path.getsize(output_json_file) >= rotation_size:
        for i in range(max_files - 1, 0, -1):
            src = f"{output_json_file}.{i}"
            dst = f"{output_json_file}.{i + 1}"
            if os.path.exists(src):
                os.rename(src, dst)
        os.rename(output_json_file, f"{output_json_file}.1")

def collect_logs():
    logs = []
    for root, dirs, files in os.walk(log_directory):
        for file in files:
            if file.endswith(".log") or file.endswith(".json"):
                file_path = os.path.join(root, file)
                with open(file_path, 'r') as f:
                    logs.append({
                        "timestamp": time.strftime("%Y-%m-%dT%H:%M:%S", time.gmtime()),
                        "log": f.read(),
                        "source": file_path
                    })

    if logs:
        with open(output_json_file, 'a') as f:
            for log in logs:
                f.write(json.dumps(log) + "\n")

if __name__ == "__main__":
    rotate_logs()
    collect_logs()
