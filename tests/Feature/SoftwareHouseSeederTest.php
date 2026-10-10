<?php

use App\Actions\Home\UpdateHomeSection;
use App\Data\Home\HeroContentData;
use App\Data\Home\PortfolioContentData;
use App\Enums\HomeSectionType;
use App\Enums\MenuLocation;
use App\Http\Requests\Admin\HomeSections\HomeSectionContentRules;
use App\Jobs\GenerateImageVariants;
use App\Models\HomeSection;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\Media\ImageVariantGenerator;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\SoftwareHouseMediaSeeder;
use Database\Seeders\SoftwareHouseSeeder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    Queue::fake();
    Notification::fake();
    Storage::fake('media');
    Storage::fake((string) config('media.public_disk'));
});

/**
 * Section types of a locale in display order.
 *
 * @return list<HomeSectionType>
 */
function showcaseOrder(string $locale): array
{
    return HomeSection::query()->where('locale', $locale)->orderBy('position')->pluck('type')->all();
}

/**
 * Top-level header items of a locale as anchor (or type) + label.
 *
 * @return list<string>
 */
function showcaseHeader(string $locale): array
{
    return MenuItem::query()
        ->where('location', MenuLocation::Header->value)
        ->where('locale', $locale)
        ->whereNull('parent_id')
        ->orderBy('position')
        ->get()
        ->map(fn (MenuItem $item): string => ($item->anchor ?? $item->type->value).':'.$item->label)
        ->all();
}

test('the showcase seeder refuses to run outside the local environment', function () {
    $this->seed(DatabaseSeeder::class);
    $before = HomeSection::query()->orderBy('id')->get()->toArray();

    expect(fn () => $this->seed(SoftwareHouseSeeder::class))->toThrow(RuntimeException::class);

    expect(HomeSection::query()->orderBy('id')->get()->toArray())->toBe($before);
});

test('the showcase seeder turns the demo into the software house home page', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);

    $this->seed(SoftwareHouseSeeder::class);

    expect(showcaseOrder('pl'))->toBe([
        HomeSectionType::Hero,
        HomeSectionType::Features,
        HomeSectionType::Portfolio,
        HomeSectionType::Team,
        HomeSectionType::Testimonials,
        HomeSectionType::Pricing,
        HomeSectionType::LatestArticles,
        HomeSectionType::Cta,
        HomeSectionType::Contact,
        HomeSectionType::Faq,
    ])->and(HomeSection::query()->where('locale', 'pl')->where('enabled', false)->orderBy('position')->pluck('type')->all())->toBe([HomeSectionType::LatestArticles, HomeSectionType::Cta, HomeSectionType::Faq]);

    $hero = HomeSection::query()->where('locale', 'pl')->where('type', HomeSectionType::Hero)->sole()->content;
    $portfolio = HomeSection::query()->where('locale', 'en')->where('type', HomeSectionType::Portfolio)->sole()->content;
    expect($hero)->toBeInstanceOf(HeroContentData::class)
        ->and($hero->imageId)->toBe(SoftwareHouseMediaSeeder::assetId('hero'))
        ->and($hero->stats)->toHaveCount(3)
        ->and($portfolio)->toBeInstanceOf(PortfolioContentData::class)
        ->and($portfolio->items)->toHaveCount(6)
        ->and(showcaseHeader('pl'))->toBe([
            'features:Usługi', 'portfolio:Portfolio', 'team:Zespół', 'pricing:Cennik', 'article_index:Blog', 'contact:Kontakt',
        ])
        ->and(showcaseHeader('en')[0])->toBe('features:Services')
        ->and(showcaseHeader('de')[0])->toBe('features:Leistungen')
        ->and(HomeSection::query()->where('locale', 'de')->where('type', HomeSectionType::Pricing)->sole()->content->title)->toBe('Preise');

    $this->get('/pl')->assertOk();
});

test('the showcase seeder fills the footer menu and the demo site settings', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);

    $this->seed(SoftwareHouseSeeder::class);

    $groups = MenuItem::query()
        ->where('location', MenuLocation::Footer->value)
        ->where('locale', 'pl')
        ->whereNull('parent_id')
        ->orderBy('position')
        ->with(['children' => fn ($query) => $query->orderBy('position')])
        ->get();
    $settings = SiteSetting::query()->find(SiteSetting::SINGLETON_ID);

    expect($groups->pluck('label')->all())->toBe(['Oferta', 'Firma'])
        ->and($groups->first()?->children->pluck('anchor')->all())->toBe(['features', 'portfolio', 'pricing'])
        ->and($settings?->contact_email)->toBe(SoftwareHouseSeeder::CONTACT_EMAIL);
});

test('a real contact e-mail saved by a person keeps the site settings', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);
    SiteSetting::query()->whereKey(SiteSetting::SINGLETON_ID)->update(['contact_email' => 'office@client.pl']);

    $this->seed(SoftwareHouseSeeder::class);

    expect(SiteSetting::query()->find(SiteSetting::SINGLETON_ID)?->contact_email)->toBe('office@client.pl');
});

test('a second run changes nothing', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);
    $this->seed(SoftwareHouseSeeder::class);
    $sections = HomeSection::query()->orderBy('id')->get()->toArray();
    $menu = MenuItem::query()->orderBy('id')->get()->toArray();

    $this->travel(1)->minute();
    $this->seed(SoftwareHouseSeeder::class);

    expect(HomeSection::query()->orderBy('id')->get()->toArray())->toBe($sections)
        ->and(MenuItem::query()->orderBy('id')->get()->toArray())->toBe($menu);
});

test('outdated seeded content nobody edited is replaced', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);
    HomeSection::query()->where('locale', 'pl')->where('type', HomeSectionType::Hero)->sole()
        ->forceFill(['content' => new HeroContentData(title: 'Copy of an older demo')])->save();

    $this->seed(SoftwareHouseSeeder::class);

    expect(showcaseOrder('pl')[2])->toBe(HomeSectionType::Portfolio)
        ->and(HomeSection::query()->where('locale', 'pl')->where('type', HomeSectionType::Hero)->sole()->content->imageId)
        ->toBe(SoftwareHouseMediaSeeder::assetId('hero'));
});

test('a locale edited by a person and an authored header menu are left untouched', function () {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);
    $hero = HomeSection::query()->where('locale', 'pl')->where('type', HomeSectionType::Hero)->sole();
    app(UpdateHomeSection::class)->handle($hero, User::factory()->editor()->create(), new HeroContentData(title: 'Edited by a person'), (string) $hero->updated_at?->toIso8601String());
    MenuItem::query()->where('location', MenuLocation::Header->value)->where('locale', 'en')->firstOrFail()
        ->forceFill(['updated_by' => User::query()->value('id')])->save();
    $polishOrder = showcaseOrder('pl');
    $englishHeader = showcaseHeader('en');

    $this->seed(SoftwareHouseSeeder::class);

    expect(showcaseOrder('pl'))->toBe($polishOrder)
        ->and(HomeSection::query()->where('locale', 'pl')->where('type', HomeSectionType::Hero)->sole()->content->title)->toBe('Edited by a person')
        ->and(showcaseHeader('en'))->toBe($englishHeader)
        ->and(showcaseOrder('en')[2])->toBe(HomeSectionType::Portfolio);
});

test('the showcase content passes the admin validation of every section', function (string $locale) {
    $this->app['env'] = 'local';
    $this->seed(DatabaseSeeder::class);
    $this->seed(SoftwareHouseSeeder::class);
    // The queue is faked: generate the public variants the DAM rule requires.
    foreach (array_keys(SoftwareHouseMediaSeeder::ASSETS) as $key) {
        (new GenerateImageVariants((int) SoftwareHouseMediaSeeder::assetId($key)))->handle(app(ImageVariantGenerator::class));
    }
    $rules = app(HomeSectionContentRules::class);

    foreach (HomeSection::query()->where('locale', $locale)->get() as $section) {
        $validator = Validator::make(['content' => $section->content->toArray()], $rules->rules($section->type, $locale));

        expect($validator->errors()->all())->toBe([], "{$section->type->value} in {$locale}");
    }
})->with(['pl', 'en', 'de']);
