#!/bin/bash
set -euo pipefail

PORT="${PORT:-80}"

# Render injects PORT; Apache must listen on it.
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s#:80#:${PORT}#g" /etc/apache2/sites-available/000-default.conf

# Tell PHP/Kirby the public site is HTTPS on the Render hostname (no :PORT).
# Prevents asset URLs like http://host:10000/assets/...
if [ -n "${RENDER_EXTERNAL_URL:-}" ] && [ -z "${KIRBY_URL:-}" ]; then
	export KIRBY_URL="${RENDER_EXTERNAL_URL}"
fi

if [ -n "${KIRBY_URL:-}" ]; then
	# Strip trailing slash
	export KIRBY_URL="${KIRBY_URL%/}"
fi

# Honor Render's forwarded proto inside Apache/PHP
cat >/etc/apache2/conf-available/render-proxy.conf <<EOF
SetEnvIf X-Forwarded-Proto https HTTPS=on
PassEnv KIRBY_URL KIRBY_DEBUG KIRBY_EMAIL_FROM
EOF
a2enconf render-proxy >/dev/null

exec apache2-foreground
