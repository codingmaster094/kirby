#!/bin/bash
set -euo pipefail

PORT="${PORT:-80}"

# Render injects PORT; Apache must listen on it.
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s#:80#:${PORT}#g" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
