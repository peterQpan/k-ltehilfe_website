from flask import Flask, request, redirect
from flask_mail import Mail, Message

app = Flask(__name__)

# Konfiguration für Strato Mailserver
app.config['MAIL_SERVER'] = 'smtp.strato.de'
app.config['MAIL_PORT'] = 587
app.config['MAIL_USE_TLS'] = True
app.config['MAIL_USERNAME'] = 'info@ingos-kleine-kältehilfe.de'  # <--- ANGEPASST
app.config['MAIL_PASSWORD'] = 'dein_passwort'   # <--- ANPASSEN

mail = Mail(app)

@app.route('/sendmail', methods=['POST'])
def sendmail():
    name = request.form.get('name')
    email = request.form.get('email')
    message = request.form.get('message')
    msg = Message('Kontaktformular', sender=email, recipients=['info@ingos-kleine-kältehilfe.de'])
    msg.body = f"Name: {name}\nE-Mail: {email}\nNachricht: {message}"
    mail.send(msg)
    return redirect('/danke.html')  # oder eine Bestätigungsseite

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
