#!/bin/sh
script_name="mysql-ca"

# DigitalOcean managed MySQL requires TLS. App Platform binds the cluster's CA
# certificate as text (${db.CA_CERT}), but PDO needs a file path, so write it
# where MYSQL_ATTR_SSL_CA points. Runs before 50-laravel-automations caches config.

if [ -z "$DB_CA_CERT" ]; then
    exit 0
fi

ca_path="${MYSQL_ATTR_SSL_CA:-/tmp/mysql-ca.pem}"

# %b turns a literal "\n" (from a single-line secret) back into newlines.
printf '%b\n' "$DB_CA_CERT" > "$ca_path"

echo "ℹ️ NOTICE ($script_name): Wrote the database CA certificate to $ca_path."
