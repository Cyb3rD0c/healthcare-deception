#!/bin/bash

# Set up logging
exec > >(tee -i /var/log/entrypoint.log)
exec 2>&1

# Start Wazuh agent services
echo "Starting Wazuh agent services..."
/var/ossec/bin/wazuh-control start

# Check status of Wazuh agent services
echo "Checking status of Wazuh agent services..."
/var/ossec/bin/wazuh-control status

# Tail logs to keep container running
tail -f /var/ossec/logs/ossec.log /var/log/entrypoint.log
