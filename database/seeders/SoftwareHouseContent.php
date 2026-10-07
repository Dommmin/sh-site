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
 * Placeholder content of the software house showcase home page in Polish,
 * English and German (other locales get the English copy). Names, figures, prices,
 * projects and testimonials are samples marked as such: replace them in the
 * admin panel before publishing.
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
        $currency = $locale === 'pl' ? 'PLN' : 'EUR';
        $sampleProject = $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.', 'Beispielprojekt: durch eine eigene Arbeit ersetzen.');
        $sampleQuote = $t('Przykładowa opinia: zastąp prawdziwą referencją klienta w panelu.', 'Sample testimonial: replace it with a real client reference in the panel.', 'Beispielbewertung: im Panel durch eine echte Kundenreferenz ersetzen.');
        $sampleClient = $t('Przykładowy klient', 'Sample client', 'Beispielkunde');
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
                imageId: $imageId('hero'),
                stats: [
                    new HeroStatData(value: '120+', label: $t('projektów (przykład)', 'projects (sample)', 'Projekte (Beispiel)')),
                    new HeroStatData(value: '10', label: $t('lat doświadczenia (przykład)', 'years of experience (sample)', 'Jahre Erfahrung (Beispiel)')),
                    new HeroStatData(value: '98%', label: $t('zadowolonych klientów (przykład)', 'happy clients (sample)', 'zufriedene Kunden (Beispiel)')),
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
                    new PortfolioItemData(title: $t('Strona firmy usługowej', 'Service company website', 'Website eines Dienstleisters'), category: $t('Strona internetowa', 'Website', 'Website'), description: $sampleProject, imageId: $imageId('project-1')),
                    new PortfolioItemData(title: $t('Sklep z odzieżą', 'Clothing shop', 'Modeshop'), category: $t('Sklep internetowy', 'Online shop', 'Onlineshop'), description: $sampleProject, imageId: $imageId('project-2')),
                    new PortfolioItemData(title: $t('Panel klienta', 'Client portal', 'Kundenportal'), category: $t('Aplikacja webowa', 'Web application', 'Webanwendung'), description: $sampleProject, imageId: $imageId('project-3')),
                    new PortfolioItemData(title: $t('Identyfikacja marki', 'Brand identity', 'Markenidentität'), category: $t('Projekt graficzny', 'Graphic design', 'Grafikdesign'), description: $sampleProject, imageId: $imageId('project-4')),
                    new PortfolioItemData(title: $t('System rezerwacji', 'Booking system', 'Buchungssystem'), category: $t('Aplikacja webowa', 'Web application', 'Webanwendung'), description: $sampleProject, imageId: $imageId('project-5')),
                    new PortfolioItemData(title: $t('Strona fundacji', 'Foundation website', 'Website einer Stiftung'), category: $t('Strona internetowa', 'Website', 'Website'), description: $sampleProject, imageId: $imageId('project-6')),
                ],
                title: 'Portfolio',
                description: $t('Wybrane realizacje dla firm, sklepów i organizacji.', 'Selected work for companies, shops and organisations.', 'Ausgewählte Projekte für Unternehmen, Shops und Organisationen.'),
            )],
            HomeSectionType::Team->value => ['enabled' => true, 'content' => new TeamContentData(
                items: [
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last', 'Vorname Nachname'), role: 'Backend developer', bio: $t('Przykładowy opis: architektura, integracje i bezpieczeństwo aplikacji.', 'Sample bio: architecture, integrations and application security.', 'Beispielprofil: Architektur, Integrationen und Anwendungssicherheit.'), photoId: $imageId('person-1'), skills: ['PHP', 'Laravel', 'PostgreSQL', 'Docker']),
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last', 'Vorname Nachname'), role: 'Frontend developer', bio: $t('Przykładowy opis: szybkie i dostępne interfejsy.', 'Sample bio: fast and accessible interfaces.', 'Beispielprofil: schnelle und barrierefreie Oberflächen.'), photoId: $imageId('person-2'), skills: ['TypeScript', 'React', 'Tailwind CSS', 'WCAG']),
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last', 'Vorname Nachname'), role: 'UI/UX designer', bio: $t('Przykładowy opis: makiety, identyfikacja wizualna i testy z użytkownikami.', 'Sample bio: wireframes, visual identity and user testing.', 'Beispielprofil: Wireframes, Corporate Design und Nutzertests.'), photoId: $imageId('person-3'), skills: ['Figma', 'Adobe Illustrator', 'Design systems']),
                ],
                title: $t('Zespół', 'Team', 'Team'),
                description: $t('Ludzie, z którymi będziesz pracować na co dzień.', 'The people you will work with every day.', 'Die Menschen, mit denen Sie täglich zusammenarbeiten.'),
            )],
            HomeSectionType::Testimonials->value => ['enabled' => true, 'content' => new TestimonialsContentData(
                items: [
                    new TestimonialItemData(author: $sampleClient, quote: $sampleQuote, role: $t('Branża usługowa', 'Services', 'Dienstleistungen')),
                    new TestimonialItemData(author: $sampleClient, quote: $sampleQuote, role: $t('Handel online', 'E-commerce', 'Onlinehandel')),
                    new TestimonialItemData(author: $sampleClient, quote: $sampleQuote, role: $t('Organizacja pozarządowa', 'Non-profit', 'Gemeinnützige Organisation')),
                ],
                title: $t('Referencje', 'Testimonials', 'Referenzen'),
            )],
            HomeSectionType::Pricing->value => ['enabled' => true, 'content' => new PricingContentData(
                items: [
                    new PricingPlanData(
                        name: $t('Wizytówka', 'Starter site', 'Visitenkarte'),
                        priceMinor: 290000,
                        currency: $currency,
                        priceFrom: true,
                        description: $t('Jedna strona z najważniejszymi informacjami o firmie.', 'A single page with the key facts about your company.', 'Eine Seite mit den wichtigsten Informationen über Ihr Unternehmen.'),
                        features: self::pick(
                            $locale,
                            ['Projekt graficzny', 'Do 5 sekcji', 'Formularz kontaktowy', 'Podstawowe SEO'],
                            ['Visual design', 'Up to 5 sections', 'Contact form', 'Basic SEO'],
                            ['Grafikdesign', 'Bis zu 5 Abschnitte', 'Kontaktformular', 'Grundlegendes SEO'],
                        ),
                        action: new HomeActionData(label: $askForQuote, target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Strona firmowa', 'Company website', 'Firmenwebsite'),
                        priceMinor: 690000,
                        currency: $currency,
                        priceFrom: true,
                        description: $t('Rozbudowana strona z blogiem i panelem do edycji treści.', 'A larger site with a blog and a content panel.', 'Eine umfangreichere Website mit Blog und Redaktionspanel.'),
                        features: self::pick(
                            $locale,
                            ['Wszystko z pakietu Wizytówka', 'Podstrony i blog', 'Panel administracyjny', 'Wersje językowe'],
                            ['Everything in Starter site', 'Subpages and blog', 'Admin panel', 'Language versions'],
                            ['Alles aus dem Paket Visitenkarte', 'Unterseiten und Blog', 'Admin-Panel', 'Sprachversionen'],
                        ),
                        highlighted: true,
                        action: new HomeActionData(label: $askForQuote, target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Sklep lub aplikacja', 'Shop or web app', 'Shop oder Web-App'),
                        currency: $currency,
                        description: $t('Projekt szyty na miarę Twoich procesów.', 'A project tailored to your processes.', 'Ein Projekt, zugeschnitten auf Ihre Prozesse.'),
                        features: self::pick(
                            $locale,
                            ['Analiza potrzeb', 'Integracje z systemami', 'Testy i wdrożenie', 'Opieka po starcie'],
                            ['Needs analysis', 'System integrations', 'Testing and launch', 'Support after launch'],
                            ['Bedarfsanalyse', 'Systemintegrationen', 'Tests und Launch', 'Betreuung nach dem Start'],
                        ),
                        action: new HomeActionData(label: $t('Umów rozmowę', 'Book a call', 'Gespräch vereinbaren'), target: HomeLinkTarget::Contact),
                    ),
                ],
                title: $t('Cennik', 'Pricing', 'Preise'),
                description: $t('Orientacyjne ceny, abyś wiedział, czego się spodziewać.', 'Indicative prices so you know what to expect.', 'Richtpreise, damit Sie wissen, womit Sie rechnen können.'),
                note: $t('Ceny przykładowe, netto. Dokładną wycenę przygotujemy po krótkiej rozmowie.', 'Sample net prices. We prepare an exact quote after a short call.', 'Beispielpreise, netto. Ein genaues Angebot erstellen wir nach einem kurzen Gespräch.'),
            )],
            HomeSectionType::LatestArticles->value => ['enabled' => true, 'content' => new LatestArticlesContentData(
                limit: 3,
                title: 'Blog',
            )],
            HomeSectionType::Cta->value => ['enabled' => true, 'content' => new CtaContentData(
                title: $t('Masz pomysł na projekt?', 'Have a project in mind?', 'Haben Sie eine Projektidee?'),
                description: $t('Napisz kilka zdań o swoim pomyśle. Odpowiemy w ciągu jednego dnia roboczego.', 'Tell us a few words about your idea. We reply within one business day.', 'Schreiben Sie uns ein paar Sätze zu Ihrer Idee. Wir antworten innerhalb eines Werktags.'),
                primaryAction: new HomeActionData(label: $t('Napisz do nas', 'Write to us', 'Schreiben Sie uns'), target: HomeLinkTarget::Contact),
            )],
            HomeSectionType::Contact->value => ['enabled' => true, 'content' => new ContactContentData(
                title: $t('Kontakt', 'Contact', 'Kontakt'),
                description: $t('Opisz krótko swój projekt, a my wrócimy z pytaniami i propozycją kolejnych kroków.', 'Briefly describe your project and we will get back with questions and next steps.', 'Beschreiben Sie kurz Ihr Projekt, und wir melden uns mit Fragen und den nächsten Schritten.'),
            )],
            HomeSectionType::Faq->value => ['enabled' => false, 'content' => new FaqContentData(
                title: $t('Najczęstsze pytania', 'Frequently asked questions', 'Häufige Fragen'),
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
