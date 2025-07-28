# apps/hl7-sim/Dockerfile
FROM python:3.11-slim

# 1) Work from /app
WORKDIR /app

# 2) If you ever need extra libs, list them here.
#    For pure-stdlib you can leave this empty.
COPY requirements.txt .
RUN if [ -s requirements.txt ]; then \
      pip install --no-cache-dir -r requirements.txt; \
    fi

# 3) Copy simulator code + messages
COPY send_hl7.py .
COPY messages/ ./messages/

# 4) Exec rights & shebang
RUN chmod +x send_hl7.py

# 5) Launch unbuffered so you see stdout immediately
ENTRYPOINT ["python", "-u", "send_hl7.py"]
