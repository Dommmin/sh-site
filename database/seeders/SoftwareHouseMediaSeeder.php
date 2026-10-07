<?php

namespace Database\Seeders;

use App\Enums\MediaStatus;

/**
 * Local placeholder images of the software house showcase, generated
 * offline with GD like {@see DemoMediaSeeder}: a hero image, six 16:9
 * project thumbnails and three square team photos. Replace them with real
 * images in the media library before publishing.
 */
class SoftwareHouseMediaSeeder extends DemoMediaSeeder
{
    /**
     * @var array<string, array{uuid: string, name: string, mime: string, width?: int, height?: int, color?: string, alt: string|null, status: MediaStatus}>
     */
    public const array ASSETS = [
        'hero' => ['uuid' => 'a5000000-0000-4000-a000-000000000001', 'name' => 'sh-hero.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 1200, 'color' => '1e3a8a', 'alt' => 'Placeholder: zespół przy pracy nad projektem', 'status' => MediaStatus::Clean],
        'project-1' => ['uuid' => 'a5000000-0000-4000-a000-000000000011', 'name' => 'sh-project-1.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '0f766e', 'alt' => 'Placeholder: zrzut ekranu strony firmowej', 'status' => MediaStatus::Clean],
        'project-2' => ['uuid' => 'a5000000-0000-4000-a000-000000000012', 'name' => 'sh-project-2.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '7c3aed', 'alt' => 'Placeholder: zrzut ekranu sklepu internetowego', 'status' => MediaStatus::Clean],
        'project-3' => ['uuid' => 'a5000000-0000-4000-a000-000000000013', 'name' => 'sh-project-3.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'be123c', 'alt' => 'Placeholder: zrzut ekranu aplikacji webowej', 'status' => MediaStatus::Clean],
        'project-4' => ['uuid' => 'a5000000-0000-4000-a000-000000000014', 'name' => 'sh-project-4.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'ca8a04', 'alt' => 'Placeholder: projekt identyfikacji wizualnej', 'status' => MediaStatus::Clean],
        'project-5' => ['uuid' => 'a5000000-0000-4000-a000-000000000015', 'name' => 'sh-project-5.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '0891b2', 'alt' => 'Placeholder: zrzut ekranu portalu z rezerwacjami', 'status' => MediaStatus::Clean],
        'project-6' => ['uuid' => 'a5000000-0000-4000-a000-000000000016', 'name' => 'sh-project-6.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '4d7c0f', 'alt' => 'Placeholder: zrzut ekranu strony fundacji', 'status' => MediaStatus::Clean],
        'person-1' => ['uuid' => 'a5000000-0000-4000-a000-000000000021', 'name' => 'sh-person-1.jpg', 'mime' => 'image/jpeg', 'width' => 900, 'height' => 900, 'color' => '57534e', 'alt' => 'Placeholder: zdjęcie osoby z zespołu', 'status' => MediaStatus::Clean],
        'person-2' => ['uuid' => 'a5000000-0000-4000-a000-000000000022', 'name' => 'sh-person-2.jpg', 'mime' => 'image/jpeg', 'width' => 900, 'height' => 900, 'color' => '9a3412', 'alt' => 'Placeholder: zdjęcie osoby z zespołu', 'status' => MediaStatus::Clean],
        'person-3' => ['uuid' => 'a5000000-0000-4000-a000-000000000023', 'name' => 'sh-person-3.jpg', 'mime' => 'image/jpeg', 'width' => 900, 'height' => 900, 'color' => '334155', 'alt' => 'Placeholder: zdjęcie osoby z zespołu', 'status' => MediaStatus::Clean],
    ];
}
