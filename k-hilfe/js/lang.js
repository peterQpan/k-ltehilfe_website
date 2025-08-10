const translations = {
  de: {
    'nav.home': 'Start',
    'nav.about': 'Über uns',
    'nav.help': 'So kannst du helfen',
    'nav.gallery': 'Galerie',
    'nav.contact': 'Kontakt',
    'hero.title': 'Ingos kleine Kältehilfe',
    'hero.subtitle': 'Gemeinsam gegen Kälte und Armut',
    'about.title': 'Über uns',
    'about.text': 'Ingos kleine Kältehilfe ist eine ehrenamtliche Initiative aus Saarbrücken. Seit 2016 unterstützen wir obdachlose und bedürftige Menschen mit warmen Mahlzeiten, Kleidung und Menschlichkeit.',
    'help.title': 'So kannst du helfen',
    'help.text': 'Wir finanzieren uns ausschließlich durch Spenden. Unterstütze uns über PayPal hand-in-hand@gmx.de, bring Sachspenden vorbei oder engagiere dich als Helfer.',
    'contact.title': 'Kontakt',
    'contact.address': 'Stengelstr. 30, 66117 Saarbrücken',
    'contact.phone': 'Telefon: +49 174 9117841',
    'contact.email': 'E-Mail: hand-in-hand@gmx.de',
    'gallery.title': 'Einblicke',
    'footer.rights': '© 2025 Ingos kleine Kältehilfe'
  },
  en: {
    'nav.home': 'Home',
    'nav.about': 'About',
    'nav.help': 'How to Help',
    'nav.gallery': 'Gallery',
    'nav.contact': 'Contact',
    'hero.title': "Ingo's small cold aid",
    'hero.subtitle': 'Together against cold and poverty',
    'about.title': 'About us',
    'about.text': "Ingo's small cold aid is a volunteer initiative from Saarbrücken. Since 2016 we support homeless and people in need with warm meals, clothing and kindness.",
    'help.title': 'How you can help',
    'help.text': 'We are funded only by donations. Support us via PayPal hand-in-hand@gmx.de, bring goods or volunteer with us.',
    'contact.title': 'Contact',
    'contact.address': 'Stengelstr. 30, 66117 Saarbrücken',
    'contact.phone': 'Phone: +49 174 9117841',
    'contact.email': 'Email: hand-in-hand@gmx.de',
    'gallery.title': 'Insights',
    'footer.rights': "© 2025 Ingo's small cold aid"
  },
  tr: {
    'nav.home': 'Ana sayfa',
    'nav.about': 'Hakkımızda',
    'nav.help': 'Nasıl yardım edebilirsin',
    'nav.gallery': 'Galeri',
    'nav.contact': 'İletişim',
    'hero.title': "Ingo'nun küçük Soğuk Yardımı",
    'hero.subtitle': 'Soğuğa ve yoksulluğa karşı birlikte',
    'about.title': 'Hakkımızda',
    'about.text': "Ingo'nun küçük Soğuk Yardımı, Saarbrücken'den gönüllü bir girişimdir. 2016'dan beri evsiz ve ihtiyaç sahibi insanlara sıcak yemek, giysi ve şefkatle destek veriyoruz.",
    'help.title': 'Nasıl yardımcı olabilirsin',
    'help.text': 'Tamamen bağışlarla finanse ediliyoruz. Bize PayPal üzerinden hand-in-hand@gmx.de adresiyle, ayni bağışlarla veya gönüllü olarak destek ol.',
    'contact.title': 'İletişim',
    'contact.address': 'Stengelstr. 30, 66117 Saarbrücken',
    'contact.phone': 'Telefon: +49 174 9117841',
    'contact.email': 'E-posta: hand-in-hand@gmx.de',
    'gallery.title': 'Görseller',
    'footer.rights': "© 2025 Ingo'nun küçük Soğuk Yardımı"
  },
  it: {
    'nav.home': 'Home',
    'nav.about': 'Chi siamo',
    'nav.help': 'Come aiutare',
    'nav.gallery': 'Galleria',
    'nav.contact': 'Contatto',
    'hero.title': 'La piccola assistenza al freddo di Ingo',
    'hero.subtitle': 'Insieme contro freddo e povertà',
    'about.title': 'Chi siamo',
    'about.text': "La piccola assistenza al freddo di Ingo è un'iniziativa volontaria di Saarbrücken. Dal 2016 sosteniamo persone senzatetto e bisognose con pasti caldi, vestiti e umanità.",
    'help.title': 'Come puoi aiutare',
    'help.text': 'Ci finanziamo solo con donazioni. Sostienici via PayPal hand-in-hand@gmx.de, porta donazioni materiali o partecipa come volontario.',
    'contact.title': 'Contatto',
    'contact.address': 'Stengelstr. 30, 66117 Saarbrücken',
    'contact.phone': 'Telefono: +49 174 9117841',
    'contact.email': 'Email: hand-in-hand@gmx.de',
    'gallery.title': 'Scorci',
    'footer.rights': '© 2025 La piccola assistenza al freddo di Ingo'
  },
  es: {
    'nav.home': 'Inicio',
    'nav.about': 'Quiénes somos',
    'nav.help': 'Cómo ayudar',
    'nav.gallery': 'Galería',
    'nav.contact': 'Contacto',
    'hero.title': 'La pequeña ayuda contra el frío de Ingo',
    'hero.subtitle': 'Juntos contra el frío y la pobreza',
    'about.title': 'Quiénes somos',
    'about.text': 'La pequeña ayuda contra el frío de Ingo es una iniciativa voluntaria de Sarrebruck. Desde 2016 apoyamos a personas sin hogar y necesitadas con comidas calientes, ropa y humanidad.',
    'help.title': 'Cómo puedes ayudar',
    'help.text': 'Nos financiamos únicamente mediante donaciones. Apóyanos vía PayPal hand-in-hand@gmx.de, trae donaciones en especie o participa como voluntario.',
    'contact.title': 'Contacto',
    'contact.address': 'Stengelstr. 30, 66117 Saarbrücken',
    'contact.phone': 'Teléfono: +49 174 9117841',
    'contact.email': 'Correo: hand-in-hand@gmx.de',
    'gallery.title': 'Imágenes',
    'footer.rights': '© 2025 La pequeña ayuda contra el frío de Ingo'
  }
};

function setLanguage(lang) {
  document.documentElement.setAttribute('lang', lang);
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (translations[lang] && translations[lang][key]) {
      el.textContent = translations[lang][key];
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const switcher = document.getElementById('languageSwitcher');
  switcher.addEventListener('change', (e) => setLanguage(e.target.value));
  setLanguage('de');
});
