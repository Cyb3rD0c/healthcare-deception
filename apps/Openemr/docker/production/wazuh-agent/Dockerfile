FROM debian:bullseye-slim

# Install dependencies
RUN apt-get update && apt-get install -y wget gnupg

# Add Wazuh GPG key
RUN wget -q -O - https://packages.wazuh.com/key/GPG-KEY-WAZUH | apt-key add -

# Add Wazuh repository
RUN echo "deb https://packages.wazuh.com/4.x/apt stable main" | tee /etc/apt/sources.list.d/wazuh.list

# Update and install Wazuh agent
RUN apt-get update && apt-get install -y wazuh-agent

# Configure wazuh-agent
COPY ossec.conf /var/ossec/etc/ossec.conf

# Create ossec group and user if they don't exist
RUN groupadd -f ossec && useradd -g ossec ossec

# Ensure correct permissions
RUN chown -R root:ossec /var/ossec && chmod -R 770 /var/ossec

# Add an entry point script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
