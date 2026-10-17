<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Database\Seeder;

/**
 * "What you get" page of the software house showcase: what every project
 * gets from the starter, each claim backed by a check that runs in CI
 * (e2e web vitals and hydration, accessibility and contrast tests, Pest on
 * two databases, secret scan), and the three pricing packages mapped to the
 * starter modules. Called by {@see SoftwareHouseSeeder}; idempotent: skipped
 * when any of its slugs already exists.
 */
class SoftwareHouseOfferPageSeeder extends Seeder
{
    /**
     * Translations as `locale => [slug, title, meta description, body]`,
     * where the body uses the blocks of {@see SoftwareHouseArticleSeeder::document()}.
     *
     * @var array<string, array{0: string, 1: string, 2: string, 3: list<array{0: string, 1: string|list<string>}>}>
     */
    public const array TRANSLATIONS = [
        'pl' => ['co-dostajesz', 'Co dostajesz', 'Szybkość, dostępność, bezpieczeństwo, RODO i wielojęzyczność w każdym projekcie, sprawdzane automatycznie przy każdej zmianie kodu.', [
            ['p', 'Każdy projekt budujemy na tym samym, sprawdzonym fundamencie. Poniżej to, co dostajesz od pierwszego dnia, i to, jak sprawdzamy to automatycznie przy każdej zmianie kodu, zanim trafi na Twoją stronę.'],
            ['h2', 'Szybkość mierzona przy każdej zmianie'],
            ['p', 'Testy ładują strony publiczne na emulowanym telefonie średniej klasy przez wolne łącze 4G. Zmiana nie przechodzi, jeśli największy element ładuje się dłużej niż 2,5 s (LCP) albo układ przesuwa się bardziej niż o 0,1 (CLS). Osobny limit pilnuje rozmiaru JavaScriptu.'],
            ['h2', 'Treść gotowa w pierwszej odpowiedzi (SSR)'],
            ['p', 'Strony publiczne renderujemy na serwerze, więc wyszukiwarki i odwiedzający dostają pełną treść od razu. Test sprawdza, że strony w każdym języku uruchamiają się w przeglądarce bez błędów.'],
            ['h2', 'Dostępność'],
            ['ul', ['Testy komponentów sprawdzają obsługę klawiaturą, fokus i etykiety pól', 'Każdy kolor akcentu ma zmierzony kontrast AA w jasnym i ciemnym motywie', 'Błędy formularza są pokazywane przy polu, a nie tylko w powiadomieniu']],
            ['h2', 'Bezpieczeństwo'],
            ['ul', ['Logowanie dwuetapowe (2FA) dla kont panelu', 'Role z uprawnieniami: administrator, redaktor i podgląd bez prawa zmian', 'Dziennik zmian: kto, co i kiedy zmienił w panelu', 'Skan sekretów w kodzie i testy na dwóch bazach danych przy każdej zmianie']],
            ['h2', 'RODO'],
            ['ul', ['Baner cookies z wyborem; analityka ładuje się dopiero po zgodzie', 'Szablony polityki prywatności i regulaminu do uzupełnienia z prawnikiem', 'Wiadomości z formularza usuwane automatycznie po okresie przechowywania', 'Panel i strony prywatne wyłączone z indeksowania']],
            ['h2', 'Wielojęzyczność'],
            ['p', 'Polski, angielski, niemiecki lub inne języki: osobny adres każdej wersji, tłumaczenia treści w panelu, mapa strony i znaczniki hreflang dla wyszukiwarek.'],
            ['h2', 'Co jest w pakietach'],
            ['ul', [
                'Start: strona główna z sekcji, formularz kontaktowy z automatyczną odpowiedzią, strony prawne, baner cookies i panel do edycji treści w jednym języku',
                'Firma: wszystko ze Startu oraz podstrony, blog z tagami, do trzech języków, biblioteka zdjęć, formularz wyceny i eksport zapytań do CSV',
                'Aplikacja: moduły szyte na miarę z panelem, rolami i historią zmian, import danych z CSV, kosz z przywracaniem i integracje przez webhook',
            ]],
            ['p', 'Każdy pakiet obejmuje wdrożenie na serwer, certyfikat SSL, codzienne kopie zapasowe i dwa tygodnie poprawek po starcie.'],
        ]],
        'en' => ['what-you-get', 'What you get', 'Speed, accessibility, security, GDPR and multiple languages in every project, checked automatically on every code change.', [
            ['p', 'We build every project on the same proven foundation. Here is what you get from day one, and how we check it automatically on every code change before it reaches your site.'],
            ['h2', 'Speed measured on every change'],
            ['p', 'Tests load the public pages on an emulated mid-range phone over slow 4G. A change does not pass if the largest element takes longer than 2.5 s to appear (LCP) or the layout shifts by more than 0.1 (CLS). A separate limit keeps the JavaScript size in check.'],
            ['h2', 'Content ready in the first response (SSR)'],
            ['p', 'Public pages are rendered on the server, so search engines and visitors get the full content at once. A test checks that the pages in every language start in the browser without errors.'],
            ['h2', 'Accessibility'],
            ['ul', ['Component tests check keyboard use, focus and field labels', 'Every accent colour has a measured AA contrast in the light and dark theme', 'Form errors are shown next to the field, not only in a notification']],
            ['h2', 'Security'],
            ['ul', ['Two-factor authentication (2FA) for panel accounts', 'Roles with permissions: administrator, editor and read-only viewer', 'Audit log: who changed what in the panel and when', 'Secret scanning and tests on two databases on every change']],
            ['h2', 'GDPR'],
            ['ul', ['A cookie banner with choices; analytics loads only after consent', 'Privacy policy and terms templates to complete with your lawyer', 'Contact form messages deleted automatically after the retention period', 'The panel and private pages kept out of search engines']],
            ['h2', 'Multiple languages'],
            ['p', 'Polish, English, German or other languages: a separate address for every version, translations edited in the panel, a sitemap and hreflang tags for search engines.'],
            ['h2', 'What the packages include'],
            ['ul', [
                'Start: a home page built from sections, a contact form with an automatic reply, legal pages, a cookie banner and a content panel in one language',
                'Company: everything in Start plus subpages, a blog with tags, up to three languages, a media library, a quote form and CSV export of enquiries',
                'Application: custom modules with a panel, roles and change history, CSV data import, a trash with restore and webhook integrations',
            ]],
            ['p', 'Every package includes deployment to a server, an SSL certificate, daily backups and two weeks of fixes after launch.'],
        ]],
        'de' => ['was-sie-bekommen', 'Was Sie bekommen', 'Geschwindigkeit, Barrierefreiheit, Sicherheit, DSGVO und Mehrsprachigkeit in jedem Projekt, bei jeder Codeänderung automatisch geprüft.', [
            ['p', 'Wir bauen jedes Projekt auf demselben bewährten Fundament. Hier sehen Sie, was Sie vom ersten Tag an bekommen und wie wir es bei jeder Codeänderung automatisch prüfen, bevor sie Ihre Website erreicht.'],
            ['h2', 'Geschwindigkeit, bei jeder Änderung gemessen'],
            ['p', 'Tests laden die öffentlichen Seiten auf einem emulierten Mittelklasse-Smartphone über langsames 4G. Eine Änderung besteht nicht, wenn das größte Element länger als 2,5 s braucht (LCP) oder sich das Layout um mehr als 0,1 verschiebt (CLS). Ein eigenes Limit begrenzt die JavaScript-Größe.'],
            ['h2', 'Inhalt in der ersten Antwort (SSR)'],
            ['p', 'Öffentliche Seiten werden auf dem Server gerendert, sodass Suchmaschinen und Besucher den vollständigen Inhalt sofort erhalten. Ein Test prüft, dass die Seiten in jeder Sprache im Browser fehlerfrei starten.'],
            ['h2', 'Barrierefreiheit'],
            ['ul', ['Komponententests prüfen Tastaturbedienung, Fokus und Feldbeschriftungen', 'Jede Akzentfarbe hat einen gemessenen AA-Kontrast im hellen und dunklen Design', 'Formularfehler stehen direkt am Feld, nicht nur in einer Benachrichtigung']],
            ['h2', 'Sicherheit'],
            ['ul', ['Zwei-Faktor-Authentifizierung (2FA) für Konten im Admin-Bereich', 'Rollen mit Rechten: Administrator, Redakteur und Lesezugriff', 'Änderungsprotokoll: wer was wann im Admin-Bereich geändert hat', 'Secret-Scan und Tests auf zwei Datenbanken bei jeder Änderung']],
            ['h2', 'DSGVO'],
            ['ul', ['Cookie-Banner mit Auswahl; Analyse lädt erst nach Einwilligung', 'Vorlagen für Datenschutzerklärung und AGB zur Abstimmung mit Ihrer Rechtsberatung', 'Nachrichten aus dem Kontaktformular werden nach der Aufbewahrungsfrist automatisch gelöscht', 'Admin-Bereich und private Seiten sind von der Indexierung ausgeschlossen']],
            ['h2', 'Mehrsprachigkeit'],
            ['p', 'Deutsch, Englisch, Polnisch oder weitere Sprachen: eine eigene Adresse je Version, Übersetzungen im Admin-Bereich, eine Sitemap und hreflang-Tags für Suchmaschinen.'],
            ['h2', 'Was die Pakete enthalten'],
            ['ul', [
                'Start: eine Startseite aus Abschnitten, ein Kontaktformular mit automatischer Antwort, Rechtsseiten, ein Cookie-Banner und ein Redaktionspanel in einer Sprache',
                'Unternehmen: alles aus Start sowie Unterseiten, ein Blog mit Tags, bis zu drei Sprachen, eine Medienbibliothek, ein Angebotsformular und CSV-Export der Anfragen',
                'Anwendung: maßgeschneiderte Module mit Panel, Rollen und Änderungsverlauf, CSV-Datenimport, Papierkorb mit Wiederherstellung und Integrationen per Webhook',
            ]],
            ['p', 'Jedes Paket umfasst die Bereitstellung auf einem Server, ein SSL-Zertifikat, tägliche Backups und zwei Wochen Korrekturen nach dem Start.'],
        ]],
    ];

    public function run(): void
    {
        if (self::pageId() !== null) {
            return;
        }

        $publishedAt = now()->subDays(3);

        DemoContent::at($publishedAt->copy()->subHours(2), function () use ($publishedAt): void {
            $page = Page::query()->create();

            foreach (self::TRANSLATIONS as $locale => [$slug, $title, $metaDescription, $blocks]) {
                $page->translations()->create([
                    'locale' => $locale,
                    'title' => $title,
                    'slug' => $slug,
                    'meta_description' => $metaDescription,
                    'body' => SoftwareHouseArticleSeeder::document($blocks),
                    'status' => PublicationStatus::Published,
                    'published_at' => $publishedAt,
                ]);
            }
        });
    }

    /**
     * Id of the seeded page, found by any of its slugs; null before seeding.
     */
    public static function pageId(): ?int
    {
        $pageId = PageTranslation::query()
            ->where(function ($query): void {
                foreach (self::TRANSLATIONS as $locale => [$slug]) {
                    $query->orWhere(fn ($match) => $match->where('locale', $locale)->where('slug', $slug));
                }
            })
            ->value('page_id');

        return is_int($pageId) ? $pageId : null;
    }
}
