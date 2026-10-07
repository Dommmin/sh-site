<?php

namespace Database\Seeders;

use App\Actions\Home\EnsureHomeSections;
use App\Enums\HomeSectionAnchor;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Models\AuditLog;
use App\Models\HomeSection;
use App\Models\MenuItem;
use App\Services\Localization\LocalizationConfig;
use App\Services\Navigation\PublicNavigation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\LaravelData\Data;

/**
 * Turn the local demo into the software house showcase: placeholder images,
 * home sections in the showcase order ({@see SoftwareHouseContent}, Polish,
 * English and German copy) and a header menu of section anchors in every
 * public locale.
 *
 * Run after the default seeders: `make artisan ARGS='db:seed --class=SoftwareHouseSeeder'`.
 * Local only, because the placeholder images skip the malware scan like
 * {@see DemoMediaSeeder}. Idempotent and safe for edits: a locale is
 * rewritten only while none of its sections was changed by a person (every
 * panel edit, toggle and reorder is audited; seeders never are) or each
 * still holds seeded content, and its header menu only while no item has an
 * author.
 */
class SoftwareHouseSeeder extends Seeder
{
    public function run(LocalizationConfig $config, EnsureHomeSections $ensure, PublicNavigation $navigation): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException('SoftwareHouseSeeder seeds placeholder media without a malware scan and runs only in the local environment.');
        }

        $this->call(SoftwareHouseMediaSeeder::class);
        $ensure->handle();

        $imageId = fn (string $key): ?int => SoftwareHouseMediaSeeder::assetId($key);

        foreach ($config->getPublicLocales() as $locale) {
            $this->seedSections($locale, SoftwareHouseContent::sections($locale, $imageId));
            $this->seedHeaderMenu($locale);
        }

        $navigation->forget();
    }

    /**
     * Header menu of one locale: anchors of the showcase sections and the
     * article list.
     *
     * @return list<array{type: MenuItemType, label: string, anchor?: string}>
     */
    public static function headerMenu(string $locale): array
    {
        $anchor = fn (HomeSectionAnchor $anchor, string $polish, string $english, string $german): array => [
            'type' => MenuItemType::Anchor,
            'anchor' => $anchor->value,
            'label' => SoftwareHouseContent::pick($locale, $polish, $english, $german),
        ];

        return [
            $anchor(HomeSectionAnchor::Features, 'Usługi', 'Services', 'Leistungen'),
            $anchor(HomeSectionAnchor::Portfolio, 'Portfolio', 'Portfolio', 'Portfolio'),
            $anchor(HomeSectionAnchor::Team, 'Zespół', 'Team', 'Team'),
            $anchor(HomeSectionAnchor::Pricing, 'Cennik', 'Pricing', 'Preise'),
            ['type' => MenuItemType::ArticleIndex, 'label' => 'Blog'],
            $anchor(HomeSectionAnchor::Contact, 'Kontakt', 'Contact', 'Kontakt'),
        ];
    }

    /**
     * @param  array<string, array{enabled: bool, content: Data}>  $definitions
     */
    private function seedSections(string $locale, array $definitions): void
    {
        $sections = HomeSection::query()->where('locale', $locale)->get()->keyBy(
            fn (HomeSection $section): string => $section->type->value,
        );

        $changedByPerson = AuditLog::query()
            ->where('subject_type', (new HomeSection)->getMorphClass())
            ->whereIn('subject_id', $sections->modelKeys())
            ->pluck('subject_id')
            ->all();

        foreach ($sections as $type => $section) {
            if (in_array($section->id, $changedByPerson, true) && ! $this->holdsSeededContent($section, $definitions[$type]['content']->toArray())) {
                return;
            }
        }

        DB::transaction(function () use ($sections, $definitions): void {
            $position = 0;
            foreach ($definitions as $type => $definition) {
                $section = $sections->get($type);

                if ($section === null) {
                    continue;
                }

                $section->forceFill([
                    'position' => ++$position,
                    'enabled' => $definition['enabled'],
                    'content' => $definition['content'],
                ])->save();
            }
        });
    }

    /**
     * Whether a section still holds content a seeder wrote: the demo, the
     * neutral placeholder or this showcase.
     *
     * @param  array<string, mixed>  $showcase
     */
    private function holdsSeededContent(HomeSection $section, array $showcase): bool
    {
        $content = $section->content->toArray();

        return $content === $showcase
            || HomeSectionSeeder::isDemo($section)
            || $content === EnsureHomeSections::placeholder($section->type, $section->locale)->toArray();
    }

    private function seedHeaderMenu(string $locale): void
    {
        $items = MenuItem::query()
            ->where('location', MenuLocation::Header->value)
            ->where('locale', $locale);

        if ((clone $items)->where(fn ($query) => $query->whereNotNull('created_by')->orWhereNotNull('updated_by'))->exists()) {
            return;
        }

        $current = (clone $items)->whereNull('parent_id')->orderBy('position')->get()
            ->map(fn (MenuItem $item): array => [$item->type, $item->anchor, $item->label])
            ->all();
        $showcase = array_map(fn (array $item): array => [$item['type'], $item['anchor'] ?? null, $item['label']], self::headerMenu($locale));

        if ($current === $showcase && ! (clone $items)->whereNotNull('parent_id')->exists()) {
            return;
        }

        DB::transaction(function () use ($items, $locale): void {
            (clone $items)->whereNotNull('parent_id')->delete();
            (clone $items)->delete();

            foreach (self::headerMenu($locale) as $index => $attributes) {
                MenuItem::query()->create([
                    ...$attributes,
                    'location' => MenuLocation::Header,
                    'locale' => $locale,
                    'position' => $index + 1,
                ]);
            }
        });
    }
}
