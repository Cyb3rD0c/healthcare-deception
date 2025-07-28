#!/bin/bash

# Start Wazuh agent
/var/ossec/bin/wazuh-control start

# Start Apache (or your main application)
/usr/sbin/apache2ctl -D FOREGROUND

# Keep the container running
tail -f /dev/null
