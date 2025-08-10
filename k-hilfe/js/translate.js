const resources = {
  de: { translation: {
    nav: { home: 'Start', about: 'Über uns', help: 'Helfen', contact: 'Kontakt', imprint: 'Impressum', privacy: 'Datenschutz' },
    hero: { title: 'Ingos kleine Kältehilfe', subtitle: 'Hilf uns, Bedürftigen Wärme und Nahrung zu geben.' },
    about: { heading: 'Über uns', text: 'Ingos kleine Kältehilfe ist eine ehrenamtliche Initiative aus Saarbrücken. Seit 2016 unterstützen wir obdachlose und bedürftige Menschen mit warmen Mahlzeiten, Kleidung und Menschlichkeit. Aus einem Facebook-Aufruf von Ingo Wilke entstand der Verein Hand in Hand e.V. mit rund 64 Helfer*innen und täglicher Essensausgabe.' },
    help: { heading: 'So kannst du helfen', text: 'Unterstütze uns mit Spenden, Sachgütern oder deiner Zeit. Jeder Beitrag hilft, täglich bis zu 100 Menschen zu versorgen.' },
    contact: { heading: 'Kontakt', address: 'Stengelstraße 30, 66117 Saarbrücken', phone: '+49 174 9117841', email: 'hand-in-hand@gmx.de' }
  }},
  en: { translation: {
    nav: { home: 'Home', about: 'About', help: 'Help', contact: 'Contact', imprint: 'Imprint', privacy: 'Privacy' },
    hero: { title: "Ingo's Small Cold Aid", subtitle: 'Help us provide warmth and food to people in need.' },
    about: { heading: 'About Us', text: "Ingo's Small Cold Aid is a volunteer initiative from Saarbrücken, Germany. Since 2016 we have supported homeless and people in need with warm meals, clothing and compassion. Born from a Facebook call by Ingo Wilke, it became the association Hand in Hand e.V. with about 64 volunteers and daily meal service." },
    help: { heading: 'How you can help', text: 'Support us with donations, supplies or your time. Every contribution helps provide for up to 100 people each day.' },
    contact: { heading: 'Contact', address: 'Stengelstrasse 30, 66117 Saarbrücken', phone: '+49 174 9117841', email: 'hand-in-hand@gmx.de' }
  }},
  tr: { translation: {
    nav: { home: 'Ana Sayfa', about: 'Hakkımızda', help: 'Destek Ol', contact: 'İletişim', imprint: 'Künye', privacy: 'Gizlilik' },
    hero: { title: 'Ingo\'nun Küçük Soğuk Yardımı', subtitle: 'İhtiyaç sahiplerine sıcaklık ve yemek sağlamamıza yardım edin.' },
    about: { heading: 'Hakkımızda', text: 'Ingo\'nun Küçük Soğuk Yardımı, Saarbrücken kökenli gönüllü bir girişimdir. 2016\'dan beri evsizlere ve ihtiyaç sahiplerine sıcak yemek, giysi ve şefkat sunuyoruz. Ingo Wilke\'nin Facebook çağrısıyla doğan bu çalışma, yaklaşık 64 gönüllünün yer aldığı Hand in Hand e.V. derneğine dönüştü.' },
    help: { heading: 'Nasıl destek olabilirsiniz', text: 'Bizi bağışlarla, malzemelerle veya zamanınızla destekleyin. Her katkı her gün 100\'e kadar kişiye hizmet etmemize yardımcı olur.' },
    contact: { heading: 'İletişim', address: 'Stengelstraße 30, 66117 Saarbrücken', phone: '+49 174 9117841', email: 'hand-in-hand@gmx.de' }
  }},
  it: { translation: {
    nav: { home: 'Home', about: 'Chi siamo', help: 'Aiutaci', contact: 'Contatti', imprint: 'Impressum', privacy: 'Privacy' },
    hero: { title: 'La piccola assistenza al freddo di Ingo', subtitle: 'Aiutaci a offrire calore e cibo a chi ne ha bisogno.' },
    about: { heading: 'Chi siamo', text: "La piccola assistenza al freddo di Ingo è un'iniziativa volontaria di Saarbrücken. Dal 2016 sosteniamo i senzatetto e le persone bisognose con pasti caldi, vestiti e umanità. Nata dall'appello di Ingo Wilke, è diventata l'associazione Hand in Hand e.V. con circa 64 volontari e servizio pasti quotidiano." },
    help: { heading: 'Come puoi aiutare', text: 'Sostienici con donazioni, beni o il tuo tempo. Ogni contributo aiuta a servire fino a 100 persone al giorno.' },
    contact: { heading: 'Contatti', address: 'Stengelstraße 30, 66117 Saarbrücken', phone: '+49 174 9117841', email: 'hand-in-hand@gmx.de' }
  }},
  es: { translation: {
    nav: { home: 'Inicio', about: 'Sobre nosotros', help: 'Ayudar', contact: 'Contacto', imprint: 'Aviso legal', privacy: 'Privacidad' },
    hero: { title: 'La pequeña ayuda contra el frío de Ingo', subtitle: 'Ayúdanos a ofrecer calor y comida a quienes lo necesitan.' },
    about: { heading: 'Sobre nosotros', text: 'La pequeña ayuda contra el frío de Ingo es una iniciativa voluntaria de Saarbrücken. Desde 2016 apoyamos a personas sin hogar y necesitadas con comidas calientes, ropa y humanidad. Nacida del llamamiento de Ingo Wilke, se convirtió en la asociación Hand in Hand e.V. con unos 64 voluntarios y servicio de comidas diario.' },
    help: { heading: 'Cómo puedes ayudar', text: 'Apóyanos con donaciones, bienes o tu tiempo. Cada contribución ayuda a atender hasta 100 personas al día.' },
    contact: { heading: 'Contacto', address: 'Stengelstraße 30, 66117 Saarbrücken', phone: '+49 174 9117841', email: 'hand-in-hand@gmx.de' }
  }}
};

i18next.init({ lng: 'de', resources }, function(err, t) {
  if (err) return console.error(err);
  updateContent();
  const selector = document.getElementById('language-select');
  if (selector) selector.value = i18next.language;
});

function updateContent() {
  document.querySelectorAll('[data-i18n]').forEach(el => {
    el.innerHTML = i18next.t(el.getAttribute('data-i18n'));
  });
  document.documentElement.setAttribute('lang', i18next.language);
}

document.addEventListener('DOMContentLoaded', function() {
  const selector = document.getElementById('language-select');
  if (selector) {
    selector.addEventListener('change', function(e) {
      i18next.changeLanguage(e.target.value, updateContent);
    });
  }
});
