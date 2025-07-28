#!/usr/bin/env python3
# apps/hl7-sim/send_hl7.py

import os, time, socket, glob, random

# where to send
HOST     = os.getenv("HL7_HOST", "mirthconnect")
PORT     = int(os.getenv("HL7_PORT", 6661))
INTERVAL = float(os.getenv("HL7_INTERVAL", 5))

# load all .hl7 files into a list
paths = glob.glob("messages/*.hl7")
if not paths:
    print("No HL7 message files found under messages/"); exit(1)

def wrap_mllp(msg_bytes):
    # 0x0b ... 0x1c 0x0d framing
    return b"\x0b" + msg_bytes + b"\x1c\r"

print(f"  HL7 Simulator will send to {HOST}:{PORT} every {INTERVAL}s")

while True:
    p = random.choice(paths)
    with open(p, "rb") as f:
        data = f.read()
    packet = wrap_mllp(data)

    try:
        with socket.create_connection((HOST, PORT), timeout=10) as s:
            s.sendall(packet)
            print(f"   • Sent {p}")
            # read ACK (MLLP response) up to 1KB
            ack = s.recv(1024)
            print(f"     ↳ ACK {ack[:50]!r}")
    except Exception as e:
        print(f"    Error: {e}")

    time.sleep(INTERVAL)
