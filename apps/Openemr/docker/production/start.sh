#!/bin/bash
service wazuh-agent start
exec apache2-foreground
