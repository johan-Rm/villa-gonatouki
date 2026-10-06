#!/bin/sh

set -eu

PROJECT_DIR=/var/www/villa-gonatouki/nuxt-modern-website
RESOURCES_DIR=/var/www/resources/villa-gonatouki
DIST_DIR="$PROJECT_DIR/dist"
DIST_TMP_DIR="$PROJECT_DIR/dist_tmp"
DEPLOYMENT_DIR="$PROJECT_DIR/deployment"
NODE_CACHE_DIR="$PROJECT_DIR/node_modules/.cache"

run_cmd() {
    label="$1"
    shift
    printf '%s | %s\n' "$(date -u +%Y-%m-%dT%H:%M:%SZ)" "$label"
    if "$@"; then
        printf '✔ %s\n\n' "$label"
    else
        printf '✖ %s\n' "$label" >&2
        exit 1
    fi
}

cd "$PROJECT_DIR"

run_cmd "Reset temporary dist directory" rm -Rf "$DIST_TMP_DIR"
run_cmd "Create temporary dist directory" mkdir -p "$DIST_TMP_DIR"

run_cmd "Generate full website" yarn run generate --no-lock --fail-on-error --full_website

# Deployment stubs are optional; an existing file must copy successfully.
for stub in .htaccess robots.txt; do
    if [ -e "$DEPLOYMENT_DIR/$stub" ]; then
        run_cmd "Copy $stub to temporary dist" cp "$DEPLOYMENT_DIR/$stub" "$DIST_TMP_DIR/"
    fi
done

run_cmd "Remove previous dist" rm -Rf "$DIST_DIR"
run_cmd "Promote temporary dist to dist" mv "$DIST_TMP_DIR" "$DIST_DIR"

run_cmd "Clear Nuxt route cache" rm -f "$PROJECT_DIR/update/nuxt_routes/"*

run_cmd "Relax permissions for cache, dist and public assets" \
    chmod -R 777 "$NODE_CACHE_DIR" "$DIST_DIR" "$RESOURCES_DIR"

printf '%s | %s\n' "$(date -u +%Y-%m-%dT%H:%M:%SZ)" "build.sh completed"

exit 0
