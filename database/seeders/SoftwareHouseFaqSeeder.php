<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Support\DemoContent\DemoFaqs;
use Illuminate\Database\Seeder;

/**
 * Questions of the software house showcase FAQ section in Polish, English
 * and German. The generic samples of {@see FaqSeeder} that nobody edited are
 * unpublished (timestamps untouched, so `--remove-demo` still recognises
 * them), so the section shows only the showcase. Called by
 * {@see SoftwareHouseSeeder}; idempotent: a question already present in its
 * language is skipped.
 */
class SoftwareHouseFaqSeeder extends Seeder
{
    /**
     * Question and answer pairs per locale, in display order.
     *
     * @var array<string, list<array{0: string, 1: string}>>
     */
    public const array FAQS = [
        'pl' => [
            ['Ile trwa realizacja projektu?', 'Strona firmowa zajmuje zwykle od trzech do sześciu tygodni, sklep lub aplikacja webowa od dwóch do czterech miesięcy. Dokładny harmonogram ustalamy po warsztacie otwierającym.'],
            ['Jak wygląda współpraca krok po kroku?', 'Zaczynamy od krótkiej rozmowy i warsztatu, potem przygotowujemy projekt, a prace programistyczne prowadzimy w tygodniowych iteracjach. Co tydzień widzisz postęp na środowisku testowym.'],
            ['Ile kosztuje strona lub aplikacja?', 'Cena zależy od zakresu, liczby języków i integracji. Po rozmowie przygotowujemy wycenę z podziałem na etapy, więc wiesz, za co płacisz.'],
            ['Czy będę mógł samodzielnie edytować treści?', 'Tak. Każdy projekt ma panel administracyjny, w którym zmienisz teksty, zdjęcia, artykuły i menu bez znajomości programowania.'],
            ['Czy zajmujecie się hostingiem i utrzymaniem?', 'Tak. W abonamencie utrzymania dbamy o serwer w UE, kopie zapasowe, aktualizacje bezpieczeństwa i monitoring dostępności.'],
            ['Do kogo należy kod źródłowy?', 'Po rozliczeniu projektu prawa autorskie do kodu i projektu graficznego przechodzą na Ciebie. Otrzymujesz dostęp do repozytorium i dokumentację.'],
        ],
        'en' => [
            ['How long does a project take?', 'A company website usually takes three to six weeks, a shop or web app two to four months. We set the exact schedule after the kick-off workshop.'],
            ['What does working with you look like?', 'We start with a short call and a workshop, then prepare the design and build in weekly iterations. Every week you see the progress on a staging environment.'],
            ['How much does a website or app cost?', 'The price depends on the scope, the number of languages and integrations. After a call we prepare a quote split into stages, so you know what you pay for.'],
            ['Will I be able to edit the content myself?', 'Yes. Every project has an admin panel where you change copy, images, articles and menus without any coding.'],
            ['Do you take care of hosting and maintenance?', 'Yes. Our maintenance plan covers an EU server, backups, security updates and uptime monitoring.'],
            ['Who owns the source code?', 'Once the project is paid, the copyright to the code and the design passes to you. You get access to the repository and the documentation.'],
        ],
        'de' => [
            ['Wie lange dauert ein Projekt?', 'Eine Firmenwebsite dauert meist drei bis sechs Wochen, ein Shop oder eine Web-App zwei bis vier Monate. Den genauen Zeitplan legen wir nach dem Kick-off-Workshop fest.'],
            ['Wie läuft die Zusammenarbeit ab?', 'Wir beginnen mit einem kurzen Gespräch und einem Workshop, erstellen dann das Design und entwickeln in wöchentlichen Iterationen. Jede Woche sehen Sie den Fortschritt auf einer Testumgebung.'],
            ['Was kostet eine Website oder App?', 'Der Preis hängt vom Umfang, der Anzahl der Sprachen und den Integrationen ab. Nach einem Gespräch erstellen wir ein Angebot nach Projektphasen.'],
            ['Kann ich Inhalte später selbst ändern?', 'Ja. Jedes Projekt hat einen Admin-Bereich, in dem Sie Texte, Bilder, Artikel und Menüs ohne Programmierkenntnisse ändern.'],
            ['Kümmern Sie sich um Hosting und Wartung?', 'Ja. Unser Betreuungsvertrag umfasst einen Server in der EU, Backups, Sicherheitsupdates und Verfügbarkeitsmonitoring.'],
            ['Wem gehört der Quellcode?', 'Nach Bezahlung des Projekts gehen die Urheberrechte an Code und Design auf Sie über. Sie erhalten Zugang zum Repository und die Dokumentation.'],
        ],
    ];

    public function run(DemoFaqs $demoFaqs): void
    {
        $now = now();

        foreach (self::FAQS as $locale => $faqs) {
            foreach ($faqs as $index => [$question, $answer]) {
                DemoContent::at($now->copy()->subDays(count($faqs) - $index), fn () => Faq::query()->firstOrCreate(
                    ['locale' => $locale, 'question' => $question],
                    ['answer' => $answer, 'published' => true, 'position' => $index + 1],
                ));
            }
        }

        $ids = [];

        foreach ($demoFaqs->records() as $faq) {
            if (! $demoFaqs->isModifiedSinceSeed($faq)) {
                $ids[] = $faq->getKey();
            }
        }

        Faq::query()->whereKey($ids)->where('published', true)->toBase()->update(['published' => false]);
    }
}
