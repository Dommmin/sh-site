<?php

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\Article;
use App\Models\ArticleTranslation;
use App\Support\DemoContent\DemoArticles;
use Illuminate\Database\Seeder;

/**
 * Blog of the software house showcase: five published articles in Polish,
 * English and German with covers from {@see SoftwareHouseMediaSeeder}. The
 * edge-case samples of {@see ArticleSeeder} that nobody edited become drafts
 * (timestamps untouched, so `--remove-demo` still recognises them), so the
 * blog and the latest articles section show only the showcase. Called by
 * {@see SoftwareHouseSeeder}; idempotent: an article with any of its slugs
 * already present is skipped.
 */
class SoftwareHouseArticleSeeder extends Seeder
{
    /**
     * Per article: cover key, days before now of the publication and
     * translations as `locale => [slug, title, excerpt, body]`, where the
     * body is a list of `['p', text]`, `['h2', text]` or `['ul', items]`.
     *
     * @var array<string, array{cover: string, days: int, translations: array<string, array{0: string, 1: string, 2: string, 3: list<array{0: string, 1: string|list<string>}>}>}>
     */
    public const array ARTICLES = [
        'mvp' => ['cover' => 'article-mvp', 'days' => 4, 'translations' => [
            'pl' => ['mvp-w-szesc-tygodni', 'Jak zbudować MVP w sześć tygodni', 'Najmniejsza wersja produktu, która sprawdzi pomysł na prawdziwych użytkownikach. Pokazujemy, jak planujemy zakres i czego świadomie nie robimy.', [
                ['p', 'MVP nie jest okrojoną wersją docelowej aplikacji. To eksperyment, który ma odpowiedzieć na jedno pytanie: czy ludzie będą z tego korzystać i za to płacić.'],
                ['h2', 'Tydzień pierwszy: jedno zadanie użytkownika'],
                ['p', 'Zaczynamy od warsztatu, na którym wybieramy jedną ścieżkę, np. „klient rezerwuje termin i dostaje potwierdzenie”. Wszystko, co jej nie służy, trafia na listę „później”.'],
                ['ul', ['Logowanie przez e-mail zamiast pięciu dostawców', 'Panel administratora oparty na gotowych komponentach', 'Płatności dopiero, gdy pojawi się popyt']],
                ['h2', 'Tygodnie 2–5: krótkie iteracje'],
                ['p', 'Co tydzień pokazujemy działającą wersję na środowisku testowym. Decyzje podejmujemy na podstawie tego, co widać na ekranie, a nie specyfikacji.'],
                ['h2', 'Tydzień szósty: start i pomiar'],
                ['p', 'Publikujemy aplikację, podłączamy analitykę bez ciasteczek i umawiamy się na przegląd wyników po miesiącu. Dopiero wtedy planujemy kolejny etap.'],
            ]],
            'en' => ['mvp-in-six-weeks', 'How to build an MVP in six weeks', 'The smallest version of a product that tests the idea with real users. Here is how we plan the scope and what we deliberately leave out.', [
                ['p', 'An MVP is not a stripped-down version of the final app. It is an experiment that answers one question: will people use it and pay for it?'],
                ['h2', 'Week one: a single user task'],
                ['p', 'We start with a workshop where we pick one path, e.g. "a customer books a slot and gets a confirmation". Everything that does not serve it goes on the "later" list.'],
                ['ul', ['E-mail sign-in instead of five providers', 'An admin panel built from ready-made components', 'Payments only once there is demand']],
                ['h2', 'Weeks 2–5: short iterations'],
                ['p', 'Every week we show a working version on a staging environment. Decisions are based on what is on the screen, not on a specification.'],
                ['h2', 'Week six: launch and measure'],
                ['p', 'We publish the app, connect cookieless analytics and schedule a review of the results after a month. Only then do we plan the next stage.'],
            ]],
            'de' => ['mvp-in-sechs-wochen', 'Ein MVP in sechs Wochen bauen', 'Die kleinste Produktversion, die eine Idee mit echten Nutzern testet. So planen wir den Umfang und das lassen wir bewusst weg.', [
                ['p', 'Ein MVP ist keine abgespeckte Version der finalen App. Es ist ein Experiment, das eine Frage beantwortet: Werden Menschen es nutzen und dafür bezahlen?'],
                ['h2', 'Woche eins: eine Nutzeraufgabe'],
                ['p', 'Wir beginnen mit einem Workshop und wählen einen Ablauf, z. B. „Kunde bucht einen Termin und erhält eine Bestätigung“. Alles andere kommt auf die Liste „später“.'],
                ['ul', ['Anmeldung per E-Mail statt fünf Anbietern', 'Ein Admin-Bereich aus fertigen Komponenten', 'Zahlungen erst, wenn Nachfrage entsteht']],
                ['h2', 'Wochen 2–5: kurze Iterationen'],
                ['p', 'Jede Woche zeigen wir eine lauffähige Version auf einer Testumgebung. Entschieden wird anhand dessen, was auf dem Bildschirm zu sehen ist.'],
                ['h2', 'Woche sechs: Start und Messung'],
                ['p', 'Wir veröffentlichen die App, binden eine cookiefreie Analyse ein und vereinbaren eine Auswertung nach einem Monat. Erst dann planen wir die nächste Stufe.'],
            ]],
        ]],
        'cost' => ['cover' => 'article-cost', 'days' => 11, 'translations' => [
            'pl' => ['ile-kosztuje-strona-firmowa', 'Ile kosztuje strona firmowa i od czego zależy cena', 'Rozbijamy wycenę na elementy: projekt, treści, liczba języków, integracje i utrzymanie. Bez widełek „od 1 do 100 tysięcy”.', [
                ['p', 'Pytanie „ile kosztuje strona” przypomina pytanie „ile kosztuje dom”. Da się jednak wskazać elementy, które najbardziej wpływają na cenę.'],
                ['h2', 'Co składa się na wycenę'],
                ['ul', ['Projekt graficzny: szablon czy projekt od zera', 'Treści i zdjęcia: gotowe czy do przygotowania', 'Liczba wersji językowych', 'Integracje: CRM, newsletter, system rezerwacji']],
                ['h2', 'Koszty po starcie'],
                ['p', 'Do budżetu warto doliczyć hosting, domenę, kopie zapasowe i aktualizacje bezpieczeństwa. W abonamencie utrzymania obejmujemy je jedną miesięczną opłatą.'],
                ['h2', 'Jak obniżyć koszt bez utraty jakości'],
                ['p', 'Najwięcej oszczędza dobrze przygotowany materiał: lista podstron, teksty i zdjęcia przed rozpoczęciem prac. Każda zmiana koncepcji w trakcie wydłuża projekt.'],
            ]],
            'en' => ['how-much-does-a-company-website-cost', 'How much does a company website cost and what drives the price', 'We break the quote down into parts: design, content, languages, integrations and maintenance. No "from 1k to 100k" ranges.', [
                ['p', 'Asking "how much is a website" is like asking "how much is a house". Still, a few elements drive the price more than anything else.'],
                ['h2', 'What goes into a quote'],
                ['ul', ['Design: a template or a custom design', 'Copy and photos: ready or to be prepared', 'Number of languages', 'Integrations: CRM, newsletter, booking system']],
                ['h2', 'Costs after launch'],
                ['p', 'Add hosting, the domain, backups and security updates to the budget. Our maintenance plan covers them in one monthly fee.'],
                ['h2', 'How to lower the cost without losing quality'],
                ['p', 'Well-prepared material saves the most: a list of pages, copy and photos before work starts. Every change of concept midway makes the project longer.'],
            ]],
            'de' => ['was-kostet-eine-firmenwebsite', 'Was kostet eine Firmenwebsite und wovon hängt der Preis ab', 'Wir zerlegen das Angebot in Bausteine: Design, Inhalte, Sprachen, Integrationen und Betreuung. Ohne Spannen „von 1 bis 100 Tausend“.', [
                ['p', 'Die Frage „Was kostet eine Website?“ ähnelt der Frage „Was kostet ein Haus?“. Einige Faktoren bestimmen den Preis aber besonders stark.'],
                ['h2', 'Was in ein Angebot einfließt'],
                ['ul', ['Design: Vorlage oder individuelles Design', 'Texte und Fotos: vorhanden oder zu erstellen', 'Anzahl der Sprachversionen', 'Integrationen: CRM, Newsletter, Buchungssystem']],
                ['h2', 'Kosten nach dem Start'],
                ['p', 'Hosting, Domain, Backups und Sicherheitsupdates gehören ins Budget. Unser Betreuungsvertrag deckt sie mit einer monatlichen Gebühr ab.'],
                ['h2', 'Kosten senken ohne Qualitätsverlust'],
                ['p', 'Am meisten spart gut vorbereitetes Material: Seitenliste, Texte und Fotos vor Projektbeginn. Jede Konzeptänderung unterwegs verlängert das Projekt.'],
            ]],
        ]],
        'stack' => ['cover' => 'article-stack', 'days' => 19, 'translations' => [
            'pl' => ['laravel-czy-no-code', 'Laravel czy no-code? Kiedy który wybór ma sens', 'Narzędzia no-code świetnie sprawdzają się na start, ale mają swoje granice. Podpowiadamy, po czym poznać, że czas na własny kod.', [
                ['p', 'Zaczynanie od narzędzi no-code to często rozsądna decyzja: szybko, tanio i bez zespołu programistów. Problem pojawia się, gdy produkt rośnie.'],
                ['h2', 'Kiedy no-code wystarczy'],
                ['ul', ['Prosta strona wizytówka lub landing page', 'Formularz zbierający zgłoszenia', 'Prototyp do rozmów z inwestorami']],
                ['h2', 'Sygnały, że czas na własny kod'],
                ['p', 'Rosnące opłaty za użytkownika, obejścia dla prostych reguł biznesowych, brak kontroli nad danymi i wydajnością. Wtedy własna aplikacja w Laravelu zwykle zwraca się w ciągu roku.'],
                ['h2', 'Jak przejść bez rewolucji'],
                ['p', 'Migrujemy etapami: najpierw dane i panel administracyjny, potem kolejne procesy. Użytkownicy nie zauważają przerwy w działaniu usługi.'],
            ]],
            'en' => ['laravel-or-no-code', 'Laravel or no-code? When each choice makes sense', 'No-code tools are great for getting started, but they have limits. Here is how to tell it is time for custom code.', [
                ['p', 'Starting with no-code tools is often a sensible decision: fast, cheap and without a development team. The problem appears once the product grows.'],
                ['h2', 'When no-code is enough'],
                ['ul', ['A simple business card site or landing page', 'A form that collects requests', 'A prototype for investor meetings']],
                ['h2', 'Signs it is time for custom code'],
                ['p', 'Growing per-user fees, workarounds for simple business rules, no control over data and performance. A custom Laravel app then usually pays for itself within a year.'],
                ['h2', 'How to switch without a revolution'],
                ['p', 'We migrate in stages: data and the admin panel first, then the remaining processes. Users notice no downtime.'],
            ]],
            'de' => ['laravel-oder-no-code', 'Laravel oder No-Code? Wann welche Wahl sinnvoll ist', 'No-Code-Tools eignen sich hervorragend für den Start, haben aber Grenzen. So erkennen Sie, dass es Zeit für eigenen Code ist.', [
                ['p', 'Mit No-Code-Tools zu starten ist oft vernünftig: schnell, günstig und ohne Entwicklerteam. Schwierig wird es, wenn das Produkt wächst.'],
                ['h2', 'Wann No-Code reicht'],
                ['ul', ['Eine einfache Visitenkarten-Website oder Landingpage', 'Ein Formular für Anfragen', 'Ein Prototyp für Investorengespräche']],
                ['h2', 'Anzeichen, dass eigener Code fällig ist'],
                ['p', 'Steigende Gebühren pro Nutzer, Umwege für einfache Geschäftsregeln, keine Kontrolle über Daten und Leistung. Eine eigene Laravel-App rechnet sich dann meist innerhalb eines Jahres.'],
                ['h2', 'Umstieg ohne Revolution'],
                ['p', 'Wir migrieren schrittweise: zuerst Daten und Admin-Bereich, dann die weiteren Prozesse. Nutzer bemerken keine Ausfallzeit.'],
            ]],
        ]],
        'speed' => ['cover' => 'article-speed', 'days' => 27, 'translations' => [
            'pl' => ['piec-poprawek-szybkosci-strony', 'Pięć poprawek, które realnie przyspieszają stronę', 'Core Web Vitals wpływają na pozycję w Google i na sprzedaż. Oto zmiany, które w naszych projektach dają największy efekt.', [
                ['p', 'Szybkość strony to nie tylko wynik w Lighthouse. Każda sekunda ładowania na telefonie obniża liczbę wysłanych formularzy i zamówień.'],
                ['h2', 'Najczęstsze poprawki'],
                ['ul', ['Zdjęcia w formacie WebP lub AVIF i w kilku rozmiarach', 'Fonty ładowane lokalnie z podzbiorem znaków', 'Renderowanie po stronie serwera dla treści publicznych', 'Mniej skryptów firm trzecich', 'Pamięć podręczna i kompresja na serwerze']],
                ['h2', 'Jak mierzymy efekt'],
                ['p', 'Porównujemy dane z prawdziwych urządzeń (CrUX) przed zmianą i miesiąc po niej. Wynik laboratoryjny traktujemy jako wskazówkę, nie cel.'],
            ]],
            'en' => ['five-fixes-for-a-faster-website', 'Five fixes that make a website genuinely faster', 'Core Web Vitals affect your Google ranking and your sales. These are the changes with the biggest impact in our projects.', [
                ['p', 'Page speed is more than a Lighthouse score. Every second of loading on a phone lowers the number of forms and orders sent.'],
                ['h2', 'The most common fixes'],
                ['ul', ['Images in WebP or AVIF and in several sizes', 'Self-hosted fonts with a character subset', 'Server-side rendering for public content', 'Fewer third-party scripts', 'Caching and compression on the server']],
                ['h2', 'How we measure the result'],
                ['p', 'We compare field data from real devices (CrUX) before the change and a month after it. A lab score is a hint, not a goal.'],
            ]],
            'de' => ['fuenf-massnahmen-fuer-schnellere-websites', 'Fünf Maßnahmen, die eine Website wirklich schneller machen', 'Core Web Vitals beeinflussen Ihr Google-Ranking und Ihren Umsatz. Diese Änderungen wirken in unseren Projekten am stärksten.', [
                ['p', 'Ladezeit ist mehr als ein Lighthouse-Wert. Jede Sekunde auf dem Handy senkt die Zahl gesendeter Formulare und Bestellungen.'],
                ['h2', 'Die häufigsten Maßnahmen'],
                ['ul', ['Bilder in WebP oder AVIF und in mehreren Größen', 'Lokal gehostete Schriften mit Zeichen-Subset', 'Serverseitiges Rendering für öffentliche Inhalte', 'Weniger Skripte von Drittanbietern', 'Caching und Komprimierung auf dem Server']],
                ['h2', 'Wie wir den Effekt messen'],
                ['p', 'Wir vergleichen Felddaten echter Geräte (CrUX) vor der Änderung und einen Monat danach. Ein Laborwert ist ein Hinweis, kein Ziel.'],
            ]],
        ]],
        'shop' => ['cover' => 'article-shop', 'days' => 36, 'translations' => [
            'pl' => ['checklista-przed-startem-sklepu', 'Checklista przed startem sklepu internetowego', 'Regulamin, płatności, wysyłka, maile transakcyjne i analityka. Lista rzeczy, które sprawdzamy przed każdym uruchomieniem sklepu.', [
                ['p', 'Start sklepu to moment, w którym drobne zaniedbania kosztują najwięcej. Dlatego przed publikacją przechodzimy z klientem tę samą listę kontrolną.'],
                ['h2', 'Formalności'],
                ['ul', ['Regulamin i polityka prywatności zgodne z ofertą', 'Informacja o prawie odstąpienia od umowy', 'Dane firmy w stopce i w mailach']],
                ['h2', 'Zakupy i płatności'],
                ['ul', ['Testowe zamówienie każdą metodą płatności', 'Maile transakcyjne we wszystkich językach', 'Koszty wysyłki zgodne z cennikiem kuriera']],
                ['h2', 'Po starcie'],
                ['p', 'Przez pierwsze dwa tygodnie codziennie sprawdzamy porzucone koszyki i błędy płatności. To najszybszy sposób, aby wyłapać problemy, których nie widać w testach.'],
            ]],
            'en' => ['online-shop-launch-checklist', 'Online shop launch checklist', 'Terms, payments, shipping, transactional e-mails and analytics. The list we go through before every shop launch.', [
                ['p', 'A shop launch is when small oversights cost the most. That is why we go through the same checklist with every client before going live.'],
                ['h2', 'Formalities'],
                ['ul', ['Terms and privacy policy that match the offer', 'Information about the right of withdrawal', 'Company details in the footer and in e-mails']],
                ['h2', 'Checkout and payments'],
                ['ul', ['A test order with every payment method', 'Transactional e-mails in every language', 'Shipping costs that match the courier price list']],
                ['h2', 'After launch'],
                ['p', 'For the first two weeks we check abandoned carts and payment errors every day. It is the fastest way to catch problems that tests do not show.'],
            ]],
            'de' => ['checkliste-vor-dem-shop-start', 'Checkliste vor dem Start eines Onlineshops', 'AGB, Zahlungen, Versand, Transaktions-E-Mails und Analyse. Die Liste, die wir vor jedem Shop-Start durchgehen.', [
                ['p', 'Beim Shop-Start kosten kleine Versäumnisse am meisten. Deshalb gehen wir vor der Veröffentlichung mit jedem Kunden dieselbe Checkliste durch.'],
                ['h2', 'Formalitäten'],
                ['ul', ['AGB und Datenschutzerklärung passend zum Angebot', 'Widerrufsbelehrung', 'Firmenangaben im Footer und in E-Mails']],
                ['h2', 'Bestellung und Zahlung'],
                ['ul', ['Eine Testbestellung mit jeder Zahlungsart', 'Transaktions-E-Mails in allen Sprachen', 'Versandkosten passend zur Preisliste des Paketdienstes']],
                ['h2', 'Nach dem Start'],
                ['p', 'In den ersten zwei Wochen prüfen wir täglich abgebrochene Warenkörbe und Zahlungsfehler. So finden wir am schnellsten Probleme, die Tests nicht zeigen.'],
            ]],
        ]],
    ];

    public function run(DemoArticles $demoArticles): void
    {
        $now = now();

        foreach (self::ARTICLES as $definition) {
            $slugs = array_map(fn (array $translation): string => $translation[0], $definition['translations']);

            if ($this->seeded($slugs)) {
                continue;
            }

            $publishedAt = $now->copy()->subDays($definition['days']);

            DemoContent::at($publishedAt->copy()->subHours(2), function () use ($definition, $publishedAt): void {
                $article = Article::query()->create([
                    'cover_media_id' => SoftwareHouseMediaSeeder::assetId($definition['cover']),
                ]);

                foreach ($definition['translations'] as $locale => [$slug, $title, $excerpt, $blocks]) {
                    $article->translations()->create([
                        'locale' => $locale,
                        'title' => $title,
                        'slug' => $slug,
                        'excerpt' => $excerpt,
                        'meta_description' => $excerpt,
                        'body' => self::document($blocks),
                        'status' => PublicationStatus::Published,
                        'published_at' => $publishedAt,
                    ]);
                }
            });
        }

        $this->hideUneditedSamples($demoArticles);
    }

    /**
     * Rich text document of the article body blocks.
     *
     * @param  list<array{0: string, 1: string|list<string>}>  $blocks
     * @return array<string, mixed>
     */
    public static function document(array $blocks): array
    {
        return ['type' => 'doc', 'content' => array_map(fn (array $block): array => match (true) {
            is_array($block[1]) => DemoDocument::list('bulletList', $block[1]),
            $block[0] === 'h2' => DemoDocument::heading(2, $block[1]),
            default => DemoDocument::paragraph($block[1]),
        }, $blocks)];
    }

    /**
     * @param  array<string, string>  $slugs
     */
    private function seeded(array $slugs): bool
    {
        foreach ($slugs as $locale => $slug) {
            if (ArticleTranslation::query()->where('locale', $locale)->where('slug', $slug)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Turn the published translations of unedited edge-case samples into
     * drafts without touching their timestamps.
     */
    private function hideUneditedSamples(DemoArticles $demoArticles): void
    {
        $ids = [];

        foreach ($demoArticles->records() as $article) {
            if (! $demoArticles->isModifiedSinceSeed($article)) {
                $ids[] = $article->getKey();
            }
        }

        ArticleTranslation::query()
            ->whereIn('article_id', $ids)
            ->where('status', PublicationStatus::Published->value)
            ->toBase()
            ->update(['status' => PublicationStatus::Draft->value]);
    }
}
