#!/bin/bash
set -eu
cd "$(dirname "$0")"

check_public() {
    local entry
    for entry in .env vendor config logs; do
        if [[ -e "public/$entry" || -L "public/$entry" ]]; then
            echo "Abbruch: public/$entry gehört nicht in den Webroot. Inhalt vor dem Build prüfen." >&2
            return 1
        fi
    done
}

if [[ -L public ]]; then
    echo "Abbruch: public ist ein symbolischer Link." >&2
    exit 1
fi
check_public
hugo --minify
check_public
for script in mail.php instagram.php image_proxy.php; do
    if [[ ! -f "public/$script" || -L "public/$script" ]]; then
        echo "Abbruch: public/$script fehlt oder ist ein symbolischer Link." >&2
        exit 1
    fi
    chmod 0644 "public/$script"
done

echo "Build fertig. Nur public/ als Webroot veröffentlichen."
echo "Vor der Freigabe auf dem Server neben dem Webroot bereitstellen:"
echo "config/.env und vendor/autoload.php für PHP lesbar, logs/ für PHP beschreibbar."
