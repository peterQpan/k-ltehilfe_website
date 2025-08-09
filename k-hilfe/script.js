const translations = {
  de: {
    'nav.home': 'Start',
    'nav.about': 'Über uns',
    'nav.help': 'So helfen',
    'nav.contact': 'Kontakt',
    'hero.title': 'Ingos kleine Kältehilfe',
    'hero.subtitle': 'Gemeinsam für Wärme und Menschlichkeit',
    'about.title': 'Über uns',
    'about.content': 'Seit 2016 unterstützt Ingos kleine Kältehilfe obdachlose und bedürftige Menschen in Saarbrücken mit warmen Mahlzeiten, Kleidung und menschlicher Nähe.',
    'help.title': 'So kannst du helfen',
    'help.content': 'Du kannst helfen durch Geld- oder Sachspenden sowie durch ehrenamtliche Mitarbeit im Team.',
    'gallery.title': 'Aktuelle Eindrücke',
    'contact.title': 'Kontakt',
    'contact.address': 'Stengelstraße 30, 66117 Saarbrücken',
    'contact.phone': '+49\u00A0174\u00A09117841',
    'contact.email': 'hand-in-hand@gmx.de'
  },
  en: {
    'nav.home': 'Home',
    'nav.about': 'About',
    'nav.help': 'Help',
    'nav.contact': 'Contact',
    'hero.title': "Ingo's Small Cold Aid",
    'hero.subtitle': 'Together for warmth and humanity',
    'about.title': 'About us',
    'about.content': "Since 2016, Ingo's small cold aid has supported homeless and needy people in Saarbrücken with warm meals, clothing and human warmth.",
    'help.title': 'How you can help',
    'help.content': 'You can help with monetary or material donations and by volunteering with the team.',
    'gallery.title': 'Latest impressions',
    'contact.title': 'Contact',
    'contact.address': 'Stengelstraße 30, 66117 Saarbrücken',
    'contact.phone': '+49\u00A0174\u00A09117841',
    'contact.email': 'hand-in-hand@gmx.de'
  },
  tr: {
    'nav.home': 'Başlangıç',
    'nav.about': 'Hakkımızda',
    'nav.help': 'Yardım',
    'nav.contact': 'İletişim',
    'hero.title': 'Ingo\'nun Küçük Soğuk Yardımı',
    'hero.subtitle': 'Sıcaklık ve insanlık için birlikte',
    'about.title': 'Hakkımızda',
    'about.content': "2016'dan beri Ingo'nun küçük Soğuk Yardımı Saarbrücken'de evsiz ve ihtiyaç sahiplerine sıcak yemek, giysi ve insan sıcaklığı sağlıyor.",
    'help.title': 'Nasıl yardımcı olabilirsiniz',
    'help.content': 'Ekipte gönüllü olarak çalışarak ve para ya da malzeme bağışlayarak yardımcı olabilirsin.',
    'gallery.title': 'Güncel görseller',
    'contact.title': 'İletişim',
    'contact.address': 'Stengelstraße 30, 66117 Saarbrücken',
    'contact.phone': '+49\u00A0174\u00A09117841',
    'contact.email': 'hand-in-hand@gmx.de'
  },
  it: {
    'nav.home': 'Start',
    'nav.about': 'Chi siamo',
    'nav.help': 'Aiuto',
    'nav.contact': 'Contatti',
    'hero.title': 'Piccola Assistenza al Freddo di Ingo',
    'hero.subtitle': 'Insieme per calore e umanità',
    'about.title': 'Chi siamo',
    'about.content': 'Dal 2016 la piccola assistenza al freddo di Ingo sostiene a Saarbrücken persone senza dimora e bisognose con pasti caldi, vestiti e calore umano.',
    'help.title': 'Come puoi aiutare',
    'help.content': 'Puoi aiutare con donazioni in denaro o beni e con il volontariato nel team.',
    'gallery.title': 'Ultime impressioni',
    'contact.title': 'Contatti',
    'contact.address': 'Stengelstraße 30, 66117 Saarbrücken',
    'contact.phone': '+49\u00A0174\u00A09117841',
    'contact.email': 'hand-in-hand@gmx.de'
  },
  es: {
    'nav.home': 'Inicio',
    'nav.about': 'Sobre nosotros',
    'nav.help': 'Ayuda',
    'nav.contact': 'Contacto',
    'hero.title': 'Pequeña Ayuda contra el Frío de Ingo',
    'hero.subtitle': 'Juntos por calor y humanidad',
    'about.title': 'Sobre nosotros',
    'about.content': 'Desde 2016 la pequeña ayuda contra el frío de Ingo apoya en Saarbrücken a personas sin hogar y necesitadas con comidas calientes, ropa y calor humano.',
    'help.title': 'Cómo puedes ayudar',
    'help.content': 'Puedes ayudar con donaciones monetarias o materiales y haciendo voluntariado en el equipo.',
    'gallery.title': 'Impresiones recientes',
    'contact.title': 'Contacto',
    'contact.address': 'Stengelstraße 30, 66117 Saarbrücken',
    'contact.phone': '+49\u00A0174\u00A09117841',
    'contact.email': 'hand-in-hand@gmx.de'
  }
};

function setLanguage(lang) {
  const elements = document.querySelectorAll('[data-i18n]');
  elements.forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (translations[lang] && translations[lang][key]) {
      el.innerText = translations[lang][key];
    }
  });
  document.documentElement.setAttribute('lang', lang);
  localStorage.setItem('lang', lang);
}

document.addEventListener('DOMContentLoaded', () => {
  const saved = localStorage.getItem('lang') || 'de';
  const selector = document.getElementById('language-switcher');
  selector.value = saved;
  setLanguage(saved);
  selector.addEventListener('change', e => setLanguage(e.target.value));
});
