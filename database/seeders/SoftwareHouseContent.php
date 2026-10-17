<?php

namespace Database\Seeders;

use App\Data\Home\ContactContentData;
use App\Data\Home\CtaContentData;
use App\Data\Home\FaqContentData;
use App\Data\Home\FeatureItemData;
use App\Data\Home\FeaturesContentData;
use App\Data\Home\HeroContentData;
use App\Data\Home\HeroStatData;
use App\Data\Home\HomeActionData;
use App\Data\Home\LatestArticlesContentData;
use App\Data\Home\PortfolioContentData;
use App\Data\Home\PortfolioItemData;
use App\Data\Home\PricingContentData;
use App\Data\Home\PricingPlanData;
use App\Data\Home\TeamContentData;
use App\Data\Home\TeamMemberData;
use App\Data\Home\TestimonialItemData;
use App\Data\Home\TestimonialsContentData;
use App\Enums\HomeIcon;
use App\Enums\HomeLinkTarget;
use App\Enums\HomeSectionType;
use Closure;
use Spatie\LaravelData\Data;

/**
 * Example content of the software house showcase home page in Polish,
 * English and German (other locales get the English copy). Clients, people,
 * figures, results, prices and testimonials are fictional but realistic, so
 * the layout can be judged with believable copy: replace every one of them
 * with real data in the admin panel before publishing.
 */
final class SoftwareHouseContent
{
    /**
     * Sections of a locale in display order with their visibility.
     *
     * @param  Closure(string): ?int  $imageId  Id of a {@see SoftwareHouseMediaSeeder} asset by key.
     * @return array<string, array{enabled: bool, content: Data}> Keyed by HomeSectionType value.
     */
    public static function sections(string $locale, Closure $imageId): array
    {
        $t = fn (string $polish, string $english, string $german): string => self::pick($locale, $polish, $english, $german);
        $pl = $locale === 'pl';
        $currency = $pl ? 'PLN' : 'EUR';
        $askForQuote = $t('Zapytaj o wycenę', 'Ask for a quote', 'Angebot anfragen');

        return [
            HomeSectionType::Hero->value => ['enabled' => true, 'content' => new HeroContentData(
                title: $t('Strony i aplikacje internetowe, które pracują na Twój biznes', 'Websites and web apps that work for your business', 'Websites und Web-Apps, die für Ihr Unternehmen arbeiten'),
                eyebrow: 'Software house',
                description: $t(
                    'Projektujemy, budujemy i utrzymujemy strony firmowe, sklepy oraz aplikacje webowe. Od pierwszej rozmowy do wdrożenia i dalszego rozwoju.',
                    'We design, build and maintain company websites, online shops and web applications. From the first call to launch and beyond.',
                    'Wir gestalten, entwickeln und betreuen Firmenwebsites, Onlineshops und Webanwendungen. Vom ersten Gespräch bis zum Launch und darüber hinaus.',
                ),
                primaryAction: new HomeActionData(label: $t('Porozmawiajmy o projekcie', 'Let’s talk about your project', 'Sprechen wir über Ihr Projekt'), target: HomeLinkTarget::Contact),
                secondaryAction: new HomeActionData(label: $t('Zobacz realizacje', 'See our work', 'Projekte ansehen'), target: HomeLinkTarget::Portfolio),
                imageId: $imageId('hero'),
                stats: [
                    new HeroStatData(value: '140+', label: $t('zrealizowanych projektów', 'projects delivered', 'umgesetzte Projekte')),
                    new HeroStatData(value: '12', label: $t('lat na rynku', 'years in business', 'Jahre am Markt')),
                    new HeroStatData(value: '98%', label: $t('zostaje na opiekę', 'stay for support', 'bleiben in Betreuung')),
                ],
            )],
            HomeSectionType::Features->value => ['enabled' => true, 'content' => new FeaturesContentData(
                items: [
                    new FeatureItemData(title: $t('Strony internetowe', 'Websites', 'Websites'), description: $t('Szybkie, responsywne strony firmowe z panelem do samodzielnej edycji treści.', 'Fast, responsive company websites with a panel to edit content yourself.', 'Schnelle, responsive Firmenwebsites mit einem Panel, in dem Sie Inhalte selbst bearbeiten.'), icon: HomeIcon::Globe),
                    new FeatureItemData(title: $t('Sklepy internetowe', 'Online shops', 'Onlineshops'), description: $t('Sprzedaż online dopasowana do Twojego asortymentu, płatności i dostaw.', 'Online sales tailored to your products, payments and shipping.', 'Onlineverkauf, abgestimmt auf Ihr Sortiment, Ihre Zahlungen und Ihren Versand.'), icon: HomeIcon::Rocket),
                    new FeatureItemData(title: $t('Aplikacje webowe', 'Web applications', 'Webanwendungen'), description: $t('Systemy rezerwacji, panele klienta i narzędzia wewnętrzne skrojone na miarę.', 'Booking systems, client portals and internal tools built to measure.', 'Buchungssysteme, Kundenportale und interne Tools nach Maß.'), icon: HomeIcon::Zap),
                    new FeatureItemData(title: $t('Projektowanie UI/UX', 'UI/UX design', 'UI/UX-Design'), description: $t('Makiety, identyfikacja wizualna i projekty interfejsów przed pierwszą linijką kodu.', 'Wireframes, visual identity and interface design before the first line of code.', 'Wireframes, Corporate Design und Interface-Entwürfe vor der ersten Codezeile.'), icon: HomeIcon::Palette),
                    new FeatureItemData(title: $t('Utrzymanie i bezpieczeństwo', 'Maintenance and security', 'Wartung und Sicherheit'), description: $t('Aktualizacje, kopie zapasowe, monitoring i szybka pomoc, gdy coś przestaje działać.', 'Updates, backups, monitoring and quick help when something stops working.', 'Updates, Backups, Monitoring und schnelle Hilfe, wenn etwas nicht mehr funktioniert.'), icon: HomeIcon::ShieldCheck),
                    new FeatureItemData(title: $t('Dostępność cyfrowa', 'Digital accessibility', 'Digitale Barrierefreiheit'), description: $t('Strony zgodne z WCAG, wygodne dla wszystkich użytkowników i wyszukiwarek.', 'WCAG compliant sites that work for every user and search engine.', 'WCAG-konforme Websites, die für alle Nutzer und Suchmaschinen funktionieren.'), icon: HomeIcon::Accessibility),
                ],
                title: $t('Usługi', 'Services', 'Leistungen'),
                description: $t('Wszystko, czego potrzebujesz, aby Twoja firma dobrze wyglądała i działała w sieci.', 'Everything your company needs to look good and work well online.', 'Alles, was Ihr Unternehmen braucht, um online gut auszusehen und gut zu funktionieren.'),
            )],
            HomeSectionType::Portfolio->value => ['enabled' => true, 'content' => new PortfolioContentData(
                items: [
                    new PortfolioItemData(title: $t('Logibox: panel klienta', 'Logibox: client portal', 'Logibox: Kundenportal'), category: $t('Aplikacja webowa', 'Web application', 'Webanwendung'), description: $t('Śledzenie przesyłek, faktury i zgłoszenia w jednym miejscu. Obsługa klienta odpowiada o 60% szybciej.', 'Shipment tracking, invoices and support tickets in one place. Customer service now replies 60% faster.', 'Sendungsverfolgung, Rechnungen und Supportanfragen an einem Ort. Der Kundenservice antwortet 60 % schneller.'), imageId: $imageId('project-3')),
                    new PortfolioItemData(title: $t('Wróbel & Partnerzy', 'Wróbel & Partners', 'Wróbel & Partner'), category: $t('Strona internetowa', 'Website', 'Website'), description: $t('Strona kancelarii z blogiem eksperckim. Dwa razy więcej zapytań w pierwszym kwartale.', 'A law firm website with an expert blog. Twice as many enquiries in the first quarter.', 'Kanzleiwebsite mit Fachblog. Doppelt so viele Anfragen im ersten Quartal.'), imageId: $imageId('project-1')),
                    new PortfolioItemData(title: $t('Norrå: sklep z odzieżą', 'Norrå: clothing shop', 'Norrå: Modeshop'), category: $t('Sklep internetowy', 'Online shop', 'Onlineshop'), description: $t('1 200 produktów i szybki checkout. Konwersja wzrosła z 1,1% do 2,3%.', '1,200 products and a fast checkout. Conversion rose from 1.1% to 2.3%.', '1.200 Produkte und ein schneller Checkout. Die Conversion stieg von 1,1 % auf 2,3 %.'), imageId: $imageId('project-2')),
                    new PortfolioItemData(title: $t('Klinika Dentica', 'Dentica clinic', 'Klinik Dentica'), category: $t('System rezerwacji', 'Booking system', 'Buchungssystem'), description: $t('Wizyta umówiona w trzech krokach i przypomnienia SMS. O 40% mniej telefonów na recepcji.', 'Appointments booked in three steps with SMS reminders. 40% fewer calls to the front desk.', 'Termine in drei Schritten mit SMS-Erinnerung. 40 % weniger Anrufe am Empfang.'), imageId: $imageId('project-5')),
                    new PortfolioItemData(title: $t('Piekarnia Zakwas', 'Zakwas bakery', 'Bäckerei Zakwas'), category: $t('Identyfikacja wizualna', 'Brand identity', 'Markenidentität'), description: $t('Logo, paleta i opakowania dla rodzinnej piekarni z trzema lokalami.', 'Logo, palette and packaging for a family bakery with three shops.', 'Logo, Farbpalette und Verpackungen für eine Familienbäckerei mit drei Filialen.'), imageId: $imageId('project-4')),
                    new PortfolioItemData(title: $t('Fundacja Otwarte Drzwi', 'Open Doors Foundation', 'Stiftung Offene Türen'), category: $t('Strona internetowa', 'Website', 'Website'), description: $t('Dostępna strona (WCAG 2.2 AA) z darowiznami online i kalendarzem wydarzeń.', 'An accessible site (WCAG 2.2 AA) with online donations and an events calendar.', 'Barrierefreie Website (WCAG 2.2 AA) mit Onlinespenden und Veranstaltungskalender.'), imageId: $imageId('project-6')),
                ],
                title: 'Portfolio',
                description: $t('Sześć projektów, mierzalne efekty dla naszych klientów.', 'Six projects, measurable results for our clients.', 'Sechs Projekte, messbare Ergebnisse für unsere Kunden.'),
            )],
            HomeSectionType::Team->value => ['enabled' => true, 'content' => new TeamContentData(
                items: [
                    new TeamMemberData(name: 'Michał Kowalczyk', role: 'Backend developer', bio: $t('Projektuje architekturę i integracje: płatności, ERP, kurierzy. Dba o to, żeby nic nie padło w piątek wieczorem.', 'Designs the architecture and integrations: payments, ERP, couriers. Makes sure nothing breaks on a Friday night.', 'Entwirft Architektur und Integrationen: Zahlungen, ERP, Versand. Sorgt dafür, dass freitagabends nichts ausfällt.'), skills: ['PHP', 'Laravel', 'PostgreSQL', 'Docker']),
                    new TeamMemberData(name: 'Julia Zielińska', role: 'Frontend developer', bio: $t('Buduje szybkie, dostępne interfejsy, które działają tak samo dobrze na telefonie, jak na monitorze.', 'Builds fast, accessible interfaces that work as well on a phone as on a desktop.', 'Baut schnelle, barrierefreie Oberflächen, die auf dem Smartphone genauso gut funktionieren wie am Desktop.'), skills: ['TypeScript', 'React', 'Tailwind CSS', 'WCAG']),
                    new TeamMemberData(name: 'Ola Nowicka', role: 'UI/UX designer', bio: $t('Zaczyna od rozmów z użytkownikami, kończy na makietach, które da się od razu wdrożyć.', 'Starts with user interviews and ends with designs that are ready to build.', 'Beginnt mit Nutzerinterviews und endet mit Entwürfen, die sofort umsetzbar sind.'), skills: ['Figma', 'Research', 'Design systems']),
                ],
                title: $t('Zespół', 'Team', 'Team'),
                description: $t('Ludzie, z którymi będziesz pracować na co dzień.', 'The people you will work with every day.', 'Die Menschen, mit denen Sie täglich zusammenarbeiten.'),
            )],
            HomeSectionType::Testimonials->value => ['enabled' => true, 'content' => new TestimonialsContentData(
                items: [
                    new TestimonialItemData(author: 'Marta Wiśniewska', quote: $t('Przyszliśmy z chaotycznym Excelem, a dostaliśmy panel, z którego nasi klienci naprawdę korzystają. Kontakt bez korporacyjnych procedur.', 'We came with a messy spreadsheet and got a portal our customers actually use. No corporate red tape along the way.', 'Wir kamen mit einer chaotischen Tabelle und bekamen ein Portal, das unsere Kunden wirklich nutzen. Ganz ohne Konzernbürokratie.'), role: $t('Dyrektorka operacyjna, Logibox', 'Head of Operations, Logibox', 'Leiterin Operations, Logibox')),
                    new TestimonialItemData(author: 'Tomasz Wróbel', quote: $t('Strona wreszcie sprowadza klientów, a nie tylko ładnie wygląda. Konkretne terminy i zero niespodzianek na fakturze.', 'The site finally brings in clients instead of just looking nice. Clear deadlines and no surprises on the invoice.', 'Die Website bringt endlich Mandanten, statt nur gut auszusehen. Klare Termine und keine Überraschungen auf der Rechnung.'), role: $t('Partner zarządzający, Wróbel & Partnerzy', 'Managing Partner, Wróbel & Partners', 'Geschäftsführender Partner, Wróbel & Partner')),
                    new TestimonialItemData(author: 'Anna Lis', quote: $t('Recepcja odetchnęła już w pierwszym tygodniu. Pacjenci sami umawiają wizyty, także w nocy.', 'The front desk could breathe again within a week. Patients book on their own, even at night.', 'Der Empfang war schon in der ersten Woche entlastet. Patienten buchen selbst, auch nachts.'), role: $t('Właścicielka, Klinika Dentica', 'Owner, Dentica clinic', 'Inhaberin, Klinik Dentica')),
                ],
                title: $t('Referencje', 'Testimonials', 'Referenzen'),
                stats: [
                    new HeroStatData(value: $t('4,9/5', '4.9/5', '4,9/5'), label: $t('w Google · 37 opinii', 'on Google · 37 reviews', 'bei Google · 37 Rezensionen')),
                    new HeroStatData(value: '24 h', label: $t('na pierwszą odpowiedź', 'to our first reply', 'bis zur ersten Antwort')),
                ],
            )],
            HomeSectionType::Pricing->value => ['enabled' => true, 'content' => new PricingContentData(
                items: [
                    new PricingPlanData(
                        name: 'Start',
                        priceMinor: $pl ? 390000 : 99000,
                        currency: $currency,
                        priceFrom: true,
                        period: $t('start w 1 tydzień', 'live in 1 week', 'online in 1 Woche'),
                        description: $t('Strona-wizytówka, która od pierwszego dnia zbiera zapytania.', 'A one-page site that collects enquiries from day one.', 'Eine Visitenkarten-Website, die vom ersten Tag an Anfragen sammelt.'),
                        features: self::pick(
                            $locale,
                            ['Strona główna z sekcji', 'Formularz z automatyczną odpowiedzią', 'Strony prawne i baner cookies', 'Panel do edycji treści', 'Jeden język'],
                            ['Home page built from sections', 'Contact form with an automatic reply', 'Legal pages and a cookie banner', 'Content panel', 'One language'],
                            ['Startseite aus Abschnitten', 'Kontaktformular mit automatischer Antwort', 'Rechtsseiten und Cookie-Banner', 'Redaktionspanel', 'Eine Sprache'],
                        ),
                        action: new HomeActionData(label: $askForQuote, target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Firma', 'Company', 'Unternehmen'),
                        priceMinor: $pl ? 790000 : 199000,
                        currency: $currency,
                        priceFrom: true,
                        period: $t('start w 2–3 tygodnie', 'live in 2–3 weeks', 'online in 2–3 Wochen'),
                        description: $t('Strona firmowa z blogiem, wersjami językowymi i obsługą zapytań.', 'A company website with a blog, language versions and enquiry handling.', 'Eine Firmenwebsite mit Blog, Sprachversionen und Anfragenverwaltung.'),
                        features: self::pick(
                            $locale,
                            ['Wszystko z pakietu Start', 'Podstrony i blog z tagami', 'Do trzech języków', 'Biblioteka zdjęć', 'Formularz wyceny i eksport zapytań do CSV'],
                            ['Everything in Start', 'Subpages and a blog with tags', 'Up to three languages', 'Media library', 'Quote form and CSV export of enquiries'],
                            ['Alles aus Start', 'Unterseiten und Blog mit Tags', 'Bis zu drei Sprachen', 'Medienbibliothek', 'Angebotsformular und CSV-Export der Anfragen'],
                        ),
                        highlighted: true,
                        action: new HomeActionData(label: $askForQuote, target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Aplikacja', 'Application', 'Anwendung'),
                        priceMinor: $pl ? 1490000 : 390000,
                        currency: $currency,
                        priceFrom: true,
                        period: $t('start w 4–6 tygodni', 'live in 4–6 weeks', 'online in 4–6 Wochen'),
                        description: $t('Panel lub system skrojony pod Twoje procesy.', 'A portal or system built around your processes.', 'Ein Portal oder System, zugeschnitten auf Ihre Prozesse.'),
                        features: self::pick(
                            $locale,
                            ['Wszystko z pakietu Firma', 'Moduły szyte na miarę', 'Role i historia zmian', 'Import danych z CSV i kosz z przywracaniem', 'Integracje przez webhook'],
                            ['Everything in Company', 'Custom modules', 'Roles and change history', 'CSV data import and a trash with restore', 'Webhook integrations'],
                            ['Alles aus Unternehmen', 'Maßgeschneiderte Module', 'Rollen und Änderungsverlauf', 'CSV-Import und Papierkorb mit Wiederherstellung', 'Integrationen per Webhook'],
                        ),
                        action: new HomeActionData(label: $t('Umów rozmowę', 'Book a call', 'Gespräch vereinbaren'), target: HomeLinkTarget::Contact),
                    ),
                ],
                title: $t('Cennik', 'Pricing', 'Preise'),
                description: $t('Trzy pakiety na tym samym fundamencie: szybkim, dostępnym i bezpiecznym.', 'Three packages on the same fast, accessible and secure foundation.', 'Drei Pakete auf demselben schnellen, barrierefreien und sicheren Fundament.'),
                note: $t(
                    'Ceny netto, orientacyjne. Każdy pakiet obejmuje wdrożenie, SSL, kopie zapasowe i dwa tygodnie poprawek. Opieka po starcie od 390 zł miesięcznie.',
                    'Indicative net prices. Every package includes deployment, SSL, backups and two weeks of fixes. Support after launch from €99 a month.',
                    'Richtpreise, netto. Jedes Paket umfasst Bereitstellung, SSL, Backups und zwei Wochen Korrekturen. Betreuung nach dem Start ab 99 € im Monat.',
                ),
            )],
            HomeSectionType::LatestArticles->value => ['enabled' => true, 'content' => new LatestArticlesContentData(
                limit: 3,
                title: 'Blog',
            )],
            HomeSectionType::Cta->value => ['enabled' => false, 'content' => new CtaContentData(
                title: $t('Masz pomysł na projekt?', 'Have a project in mind?', 'Haben Sie eine Projektidee?'),
                description: $t('Napisz kilka zdań o swoim pomyśle. Odpowiemy w ciągu jednego dnia roboczego.', 'Tell us a few words about your idea. We reply within one business day.', 'Schreiben Sie uns ein paar Sätze zu Ihrer Idee. Wir antworten innerhalb eines Werktags.'),
                primaryAction: new HomeActionData(label: $t('Napisz do nas', 'Write to us', 'Schreiben Sie uns'), target: HomeLinkTarget::Contact),
            )],
            HomeSectionType::Faq->value => ['enabled' => true, 'content' => new FaqContentData(
                title: $t('Najczęstsze pytania', 'Frequently asked questions', 'Häufige Fragen'),
                description: $t('Nie ma tu Twojego pytania? Napisz do nas przez formularz poniżej.', 'Your question is not here? Write to us using the form below.', 'Ihre Frage ist nicht dabei? Schreiben Sie uns über das Formular unten.'),
            )],
            HomeSectionType::Contact->value => ['enabled' => true, 'content' => new ContactContentData(
                title: $t('Kontakt', 'Contact', 'Kontakt'),
                description: $t('Opisz krótko swój projekt, a my wrócimy z pytaniami i propozycją kolejnych kroków.', 'Briefly describe your project and we will get back with questions and next steps.', 'Beschreiben Sie kurz Ihr Projekt, und wir melden uns mit Fragen und den nächsten Schritten.'),
            )],
        ];
    }

    /**
     * The Polish, German or (for every other locale) English variant.
     *
     * @template T
     *
     * @param  T  $polish
     * @param  T  $english
     * @param  T  $german
     * @return T
     */
    public static function pick(string $locale, mixed $polish, mixed $english, mixed $german): mixed
    {
        return match ($locale) {
            'pl' => $polish,
            'de' => $german,
            default => $english,
        };
    }
}
