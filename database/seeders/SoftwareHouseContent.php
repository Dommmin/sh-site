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
 * Placeholder content of the software house showcase home page in Polish
 * and English (other locales get the English copy). Names, figures, prices,
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
        $pl = $locale === 'pl';
        $t = fn (string $polish, string $english): string => $pl ? $polish : $english;

        return [
            HomeSectionType::Hero->value => ['enabled' => true, 'content' => new HeroContentData(
                title: $t('Strony i aplikacje internetowe, które pracują na Twój biznes', 'Websites and web apps that work for your business'),
                eyebrow: $t('Software house', 'Software house'),
                description: $t(
                    'Projektujemy, budujemy i utrzymujemy strony firmowe, sklepy oraz aplikacje webowe. Od pierwszej rozmowy do wdrożenia i dalszego rozwoju.',
                    'We design, build and maintain company websites, online shops and web applications. From the first call to launch and beyond.',
                ),
                primaryAction: new HomeActionData(label: $t('Porozmawiajmy o projekcie', 'Let’s talk about your project'), target: HomeLinkTarget::Contact),
                imageId: $imageId('hero'),
                stats: [
                    new HeroStatData(value: '120+', label: $t('projektów (przykład)', 'projects (sample)')),
                    new HeroStatData(value: '10', label: $t('lat doświadczenia (przykład)', 'years of experience (sample)')),
                    new HeroStatData(value: '98%', label: $t('zadowolonych klientów (przykład)', 'happy clients (sample)')),
                ],
            )],
            HomeSectionType::Features->value => ['enabled' => true, 'content' => new FeaturesContentData(
                items: [
                    new FeatureItemData(title: $t('Strony internetowe', 'Websites'), description: $t('Szybkie, responsywne strony firmowe z panelem do samodzielnej edycji treści.', 'Fast, responsive company websites with a panel to edit content yourself.'), icon: HomeIcon::Globe),
                    new FeatureItemData(title: $t('Sklepy internetowe', 'Online shops'), description: $t('Sprzedaż online dopasowana do Twojego asortymentu, płatności i dostaw.', 'Online sales tailored to your products, payments and shipping.'), icon: HomeIcon::Rocket),
                    new FeatureItemData(title: $t('Aplikacje webowe', 'Web applications'), description: $t('Systemy rezerwacji, panele klienta i narzędzia wewnętrzne skrojone na miarę.', 'Booking systems, client portals and internal tools built to measure.'), icon: HomeIcon::Zap),
                    new FeatureItemData(title: $t('Projektowanie UI/UX', 'UI/UX design'), description: $t('Makiety, identyfikacja wizualna i projekty interfejsów przed pierwszą linijką kodu.', 'Wireframes, visual identity and interface design before the first line of code.'), icon: HomeIcon::Palette),
                    new FeatureItemData(title: $t('Utrzymanie i bezpieczeństwo', 'Maintenance and security'), description: $t('Aktualizacje, kopie zapasowe, monitoring i szybka pomoc, gdy coś przestaje działać.', 'Updates, backups, monitoring and quick help when something stops working.'), icon: HomeIcon::ShieldCheck),
                    new FeatureItemData(title: $t('Dostępność cyfrowa', 'Digital accessibility'), description: $t('Strony zgodne z WCAG, wygodne dla wszystkich użytkowników i wyszukiwarek.', 'WCAG compliant sites that work for every user and search engine.'), icon: HomeIcon::Accessibility),
                ],
                title: $t('Usługi', 'Services'),
                description: $t('Wszystko, czego potrzebujesz, aby Twoja firma dobrze wyglądała i działała w sieci.', 'Everything your company needs to look good and work well online.'),
            )],
            HomeSectionType::Portfolio->value => ['enabled' => true, 'content' => new PortfolioContentData(
                items: [
                    new PortfolioItemData(title: $t('Strona firmy usługowej', 'Service company website'), category: $t('Strona internetowa', 'Website'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-1')),
                    new PortfolioItemData(title: $t('Sklep z odzieżą', 'Clothing shop'), category: $t('Sklep internetowy', 'Online shop'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-2')),
                    new PortfolioItemData(title: $t('Panel klienta', 'Client portal'), category: $t('Aplikacja webowa', 'Web application'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-3')),
                    new PortfolioItemData(title: $t('Identyfikacja marki', 'Brand identity'), category: $t('Projekt graficzny', 'Graphic design'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-4')),
                    new PortfolioItemData(title: $t('System rezerwacji', 'Booking system'), category: $t('Aplikacja webowa', 'Web application'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-5')),
                    new PortfolioItemData(title: $t('Strona fundacji', 'Foundation website'), category: $t('Strona internetowa', 'Website'), description: $t('Projekt przykładowy: zastąp własną realizacją.', 'Sample project: replace it with your own work.'), imageId: $imageId('project-6')),
                ],
                title: $t('Portfolio', 'Portfolio'),
                description: $t('Wybrane realizacje dla firm, sklepów i organizacji.', 'Selected work for companies, shops and organisations.'),
            )],
            HomeSectionType::Team->value => ['enabled' => true, 'content' => new TeamContentData(
                items: [
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last'), role: 'Backend developer', bio: $t('Przykładowy opis: architektura, integracje i bezpieczeństwo aplikacji.', 'Sample bio: architecture, integrations and application security.'), photoId: $imageId('person-1'), skills: ['PHP', 'Laravel', 'PostgreSQL', 'Docker']),
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last'), role: 'Frontend developer', bio: $t('Przykładowy opis: szybkie i dostępne interfejsy.', 'Sample bio: fast and accessible interfaces.'), photoId: $imageId('person-2'), skills: ['TypeScript', 'React', 'Tailwind CSS', 'WCAG']),
                    new TeamMemberData(name: $t('Imię Nazwisko', 'First Last'), role: 'UI/UX designer', bio: $t('Przykładowy opis: makiety, identyfikacja wizualna i testy z użytkownikami.', 'Sample bio: wireframes, visual identity and user testing.'), photoId: $imageId('person-3'), skills: ['Figma', 'Adobe Illustrator', 'Design systems']),
                ],
                title: $t('Zespół', 'Team'),
                description: $t('Ludzie, z którymi będziesz pracować na co dzień.', 'The people you will work with every day.'),
            )],
            HomeSectionType::Testimonials->value => ['enabled' => true, 'content' => new TestimonialsContentData(
                items: [
                    new TestimonialItemData(author: $t('Przykładowy klient', 'Sample client'), quote: $t('Przykładowa opinia: zastąp prawdziwą referencją klienta w panelu.', 'Sample testimonial: replace it with a real client reference in the panel.'), role: $t('Branża usługowa', 'Services')),
                    new TestimonialItemData(author: $t('Przykładowy klient', 'Sample client'), quote: $t('Przykładowa opinia: zastąp prawdziwą referencją klienta w panelu.', 'Sample testimonial: replace it with a real client reference in the panel.'), role: $t('Handel online', 'E-commerce')),
                    new TestimonialItemData(author: $t('Przykładowy klient', 'Sample client'), quote: $t('Przykładowa opinia: zastąp prawdziwą referencją klienta w panelu.', 'Sample testimonial: replace it with a real client reference in the panel.'), role: $t('Organizacja pozarządowa', 'Non-profit')),
                ],
                title: $t('Referencje', 'Testimonials'),
            )],
            HomeSectionType::Pricing->value => ['enabled' => true, 'content' => new PricingContentData(
                items: [
                    new PricingPlanData(
                        name: $t('Wizytówka', 'Starter site'),
                        priceMinor: 290000,
                        currency: $pl ? 'PLN' : 'EUR',
                        priceFrom: true,
                        description: $t('Jedna strona z najważniejszymi informacjami o firmie.', 'A single page with the key facts about your company.'),
                        features: $pl
                            ? ['Projekt graficzny', 'Do 5 sekcji', 'Formularz kontaktowy', 'Podstawowe SEO']
                            : ['Visual design', 'Up to 5 sections', 'Contact form', 'Basic SEO'],
                        action: new HomeActionData(label: $t('Zapytaj o wycenę', 'Ask for a quote'), target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Strona firmowa', 'Company website'),
                        priceMinor: 690000,
                        currency: $pl ? 'PLN' : 'EUR',
                        priceFrom: true,
                        description: $t('Rozbudowana strona z blogiem i panelem do edycji treści.', 'A larger site with a blog and a content panel.'),
                        features: $pl
                            ? ['Wszystko z pakietu Wizytówka', 'Podstrony i blog', 'Panel administracyjny', 'Wersje językowe']
                            : ['Everything in Starter site', 'Subpages and blog', 'Admin panel', 'Language versions'],
                        highlighted: true,
                        action: new HomeActionData(label: $t('Zapytaj o wycenę', 'Ask for a quote'), target: HomeLinkTarget::Contact),
                    ),
                    new PricingPlanData(
                        name: $t('Sklep lub aplikacja', 'Shop or web app'),
                        currency: $pl ? 'PLN' : 'EUR',
                        description: $t('Projekt szyty na miarę Twoich procesów.', 'A project tailored to your processes.'),
                        features: $pl
                            ? ['Analiza potrzeb', 'Integracje z systemami', 'Testy i wdrożenie', 'Opieka po starcie']
                            : ['Needs analysis', 'System integrations', 'Testing and launch', 'Support after launch'],
                        action: new HomeActionData(label: $t('Umów rozmowę', 'Book a call'), target: HomeLinkTarget::Contact),
                    ),
                ],
                title: $t('Cennik', 'Pricing'),
                description: $t('Orientacyjne ceny, abyś wiedział, czego się spodziewać.', 'Indicative prices so you know what to expect.'),
                note: $t('Ceny przykładowe, netto. Dokładną wycenę przygotujemy po krótkiej rozmowie.', 'Sample net prices. We prepare an exact quote after a short call.'),
            )],
            HomeSectionType::LatestArticles->value => ['enabled' => true, 'content' => new LatestArticlesContentData(
                limit: 3,
                title: $t('Blog', 'Blog'),
            )],
            HomeSectionType::Cta->value => ['enabled' => true, 'content' => new CtaContentData(
                title: $t('Masz pomysł na projekt?', 'Have a project in mind?'),
                description: $t('Napisz kilka zdań o swoim pomyśle. Odpowiemy w ciągu jednego dnia roboczego.', 'Tell us a few words about your idea. We reply within one business day.'),
                primaryAction: new HomeActionData(label: $t('Napisz do nas', 'Write to us'), target: HomeLinkTarget::Contact),
            )],
            HomeSectionType::Contact->value => ['enabled' => true, 'content' => new ContactContentData(
                title: $t('Kontakt', 'Contact'),
                description: $t('Opisz krótko swój projekt, a my wrócimy z pytaniami i propozycją kolejnych kroków.', 'Briefly describe your project and we will get back with questions and next steps.'),
            )],
            HomeSectionType::Faq->value => ['enabled' => false, 'content' => new FaqContentData(
                title: $t('Najczęstsze pytania', 'Frequently asked questions'),
            )],
        ];
    }
}
