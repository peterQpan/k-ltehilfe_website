#!/bin/bash
set -eu
cd "$(dirname "$0")"

REFERENCE_COMMIT="0bf3ddb3082eb68eeded5131524420901ab564b3"
MAIL_REFERENCE_SHA="901b336304b5e45a7b6660b7dc5ef43ea821e9f5d447a359afeaab90ad909502"
MEMBER_REFERENCE_SHA="42eb55fc340aa15ce0106b03ff43532e31ce14c34ae6acd7f785eb462bfe182d"
NEWSLETTER_REFERENCE_SHA="2e037b48670a358493dbc26539fa41071764a238063a5161fe3fd715c19a3a6e"

check_public() {
    local entry
    for entry in .env vendor config logs; do
        if [[ -e "public/$entry" || -L "public/$entry" ]]; then
            echo "Abbruch: public/$entry gehört nicht in den Webroot. Inhalt vor dem Build prüfen." >&2
            return 1
        fi
    done
}

preflight_publication_changes() {
    local deviations=0
    local actual_sha
    local count
    local start_marker="{{ if .Site.Params.newsletter_enabled }}"
    local end_marker="{{ end }}"
    local newsletter_block
    newsletter_block=$(mktemp)

    if [ ! -f "static/mail.php" ]; then
        echo "Abweichung: Mail | static/mail.php | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        deviations=$((deviations + 1))
    else
        actual_sha=$(sha256sum "static/mail.php" | awk '{print $1}')
        if [ "$actual_sha" != "$MAIL_REFERENCE_SHA" ]; then
            echo "Abweichung: Mail | static/mail.php | Inhalt geändert (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        fi
    fi

    if [ ! -f "layouts/partials/become_member.html" ]; then
        echo "Abweichung: Mitglieder | layouts/partials/become_member.html | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        deviations=$((deviations + 1))
    else
        actual_sha=$(sha256sum "layouts/partials/become_member.html" | awk '{print $1}')
        if [ "$actual_sha" != "$MEMBER_REFERENCE_SHA" ]; then
            echo "Abweichung: Mitglieder | layouts/partials/become_member.html | Inhalt geändert (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        fi
    fi

    if [ ! -f "layouts/index.html" ]; then
        echo "Abweichung: Mitglieder | layouts/index.html | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        echo "Abweichung: Newsletter | layouts/index.html | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        deviations=$((deviations + 2))
    else
        count=$(grep -Fxc '{{ partial "become_member.html"}}' "layouts/index.html" || true)
        if [ "$count" -eq 0 ]; then
            echo "Abweichung: Mitglieder | layouts/index.html | Einbindung fehlt (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        elif [ "$count" -gt 1 ]; then
            echo "Abweichung: Mitglieder | layouts/index.html | Einbindung mehrdeutig: $count Treffer (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        fi

        count=$(grep -Fxc '{{ partial "aktuelles.html" . }}' "layouts/index.html" || true)
        if [ "$count" -eq 0 ]; then
            echo "Abweichung: Newsletter | layouts/index.html | Einbindung fehlt (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        elif [ "$count" -gt 1 ]; then
            echo "Abweichung: Newsletter | layouts/index.html | Einbindung mehrdeutig: $count Treffer (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        fi
    fi

    if [ ! -f "layouts/partials/aktuelles.html" ]; then
        echo "Abweichung: Newsletter | layouts/partials/aktuelles.html | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        deviations=$((deviations + 1))
    else
        count=$(grep -Fc "$start_marker" "layouts/partials/aktuelles.html" || true)
        if [ "$count" -eq 0 ]; then
            echo "Abweichung: Newsletter | layouts/partials/aktuelles.html | Formular-/Handler-Abschnitt fehlt (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        elif [ "$count" -gt 1 ]; then
            echo "Abweichung: Newsletter | layouts/partials/aktuelles.html | Formular-/Handler-Abschnitt mehrdeutig: $count Treffer (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        else
            awk -v start="$start_marker" -v end="$end_marker" '
                index($0, start) { inside = 1 }
                inside { print }
                inside && index($0, end) { exit }
            ' "layouts/partials/aktuelles.html" > "$newsletter_block"
            actual_sha=$(sha256sum "$newsletter_block" | awk '{print $1}')
            if [ "$actual_sha" != "$NEWSLETTER_REFERENCE_SHA" ]; then
                echo "Abweichung: Newsletter | layouts/partials/aktuelles.html | Formular-/Handler-Abschnitt geändert (Referenz: $REFERENCE_COMMIT)"
                deviations=$((deviations + 1))
            fi
        fi
    fi

    if [ ! -f "hugo.toml" ]; then
        echo "Abweichung: Newsletter | hugo.toml | Datei fehlt (Referenz: $REFERENCE_COMMIT)"
        deviations=$((deviations + 1))
    else
        count=$(grep -Ec '^[[:space:]]*newsletter_enabled[[:space:]]*=' "hugo.toml" || true)
        if [ "$count" -eq 0 ]; then
            echo "Abweichung: Newsletter | hugo.toml | newsletter_enabled fehlt (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        elif [ "$count" -gt 1 ]; then
            echo "Abweichung: Newsletter | hugo.toml | newsletter_enabled mehrdeutig: $count Treffer (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        elif ! grep -Eq '^[[:space:]]*newsletter_enabled[[:space:]]*=[[:space:]]*false[[:space:]]*$' "hugo.toml"; then
            echo "Abweichung: Newsletter | hugo.toml | newsletter_enabled ist nicht false (Referenz: $REFERENCE_COMMIT)"
            deviations=$((deviations + 1))
        fi
    fi

    rm -f "$newsletter_block"
    if [ "$deviations" -gt 0 ]; then
        echo "❌ Veröffentlichung blockiert: $deviations Abweichung(en) gefunden."
        return 1
    fi
    echo "✅ Änderungsschutz geprüft: Keine Abweichungen."
}

if [[ -L public ]]; then
    echo "Abbruch: public ist ein symbolischer Link." >&2
    exit 1
fi

preflight_publication_changes
check_public
hugo --minify
python3 scripts/check_member_form.py
check_public

for script in mail.php instagram.php image_proxy.php; do
    if [[ ! -f "public/$script" || -L "public/$script" ]]; then
        echo "Abbruch: public/$script fehlt oder ist ein symbolischer Link." >&2
        exit 1
    fi
    preflight_publication_changes
    check_public
    chmod 0644 "public/$script"
done

echo "Build fertig. Nur public/ als Webroot veröffentlichen."
echo "Vor der Freigabe auf dem Server neben dem Webroot bereitstellen:"
echo "config/.env und vendor/autoload.php für PHP lesbar, logs/ für PHP beschreibbar."
