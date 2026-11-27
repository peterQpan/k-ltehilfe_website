from html.parser import HTMLParser
from pathlib import Path


class MemberForm(HTMLParser):
    def __init__(self):
        super().__init__()
        self.forms = []
        self.current = None

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag == "form":
            self.current = None
            if "volunteer-form" in (attrs.get("class") or "").split():
                self.current = {}
                self.forms.append((attrs, self.current))
        elif tag == "input" and self.current is not None:
            self.current[attrs.get("name")] = attrs

    def handle_endtag(self, tag):
        if tag == "form":
            self.current = None


def main():
    parser = MemberForm()
    parser.feed(Path("public/index.html").read_text(encoding="utf-8"))
    parser.close()
    if len(parser.forms) != 1:
        raise SystemExit("Abbruch: public/index.html muss genau ein Mitgliederformular enthalten.")
    form, fields = parser.forms[0]
    if form.get("action") != "/mail.php" or (form.get("method") or "").lower() != "post":
        raise SystemExit("Abbruch: Das Mitgliederformular muss per POST an /mail.php senden.")
    for name in ("volunteer-name", "volunteer-email", "volunteer-subject"):
        if name not in fields or "required" not in fields[name] or "disabled" in fields[name]:
            raise SystemExit(f"Abbruch: Pflichtfeld {name} fehlt oder ist nicht aktiv.")


if __name__ == "__main__":
    main()
