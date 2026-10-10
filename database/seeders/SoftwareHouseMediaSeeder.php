<?php

namespace Database\Seeders;

use App\Enums\MediaStatus;
use Intervention\Image\Geometry\Factories\EllipseFactory;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * Local placeholder images of the software house showcase, generated
 * offline with GD like {@see DemoMediaSeeder}: a hero image and six 16:9
 * project thumbnails drawn as interface mock-ups (browser window, layout
 * blocks) in one warm palette, so the page reads like a real portfolio.
 * Team members have no photo and show their initials. Replace the images
 * with real screenshots in the media library before publishing.
 */
class SoftwareHouseMediaSeeder extends DemoMediaSeeder
{
    /**
     * @var array<string, array{uuid: string, name: string, mime: string, width?: int, height?: int, color?: string, alt: string|null, status: MediaStatus}>
     */
    public const array ASSETS = [
        'hero' => ['uuid' => 'a5000000-0000-4000-a000-000000000101', 'name' => 'sh-hero.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 1200, 'color' => '1c1917', 'alt' => 'Placeholder: panel aplikacji webowej z wykresami', 'status' => MediaStatus::Clean],
        'project-1' => ['uuid' => 'a5000000-0000-4000-a000-000000000211', 'name' => 'sh-project-1.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'e9dfd1', 'alt' => 'Placeholder: strona firmowa', 'status' => MediaStatus::Clean],
        'project-2' => ['uuid' => 'a5000000-0000-4000-a000-000000000212', 'name' => 'sh-project-2.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '1f2a24', 'alt' => 'Placeholder: sklep internetowy', 'status' => MediaStatus::Clean],
        'project-3' => ['uuid' => 'a5000000-0000-4000-a000-000000000233', 'name' => 'sh-project-3.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'dfe3e8', 'alt' => 'Placeholder: panel klienta', 'status' => MediaStatus::Clean],
        'project-4' => ['uuid' => 'a5000000-0000-4000-a000-000000000214', 'name' => 'sh-project-4.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'c2410c', 'alt' => 'Placeholder: identyfikacja wizualna', 'status' => MediaStatus::Clean],
        'project-5' => ['uuid' => 'a5000000-0000-4000-a000-000000000215', 'name' => 'sh-project-5.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => 'ece6dc', 'alt' => 'Placeholder: system rezerwacji', 'status' => MediaStatus::Clean],
        'project-6' => ['uuid' => 'a5000000-0000-4000-a000-000000000216', 'name' => 'sh-project-6.jpg', 'mime' => 'image/jpeg', 'width' => 1600, 'height' => 900, 'color' => '292524', 'alt' => 'Placeholder: strona fundacji', 'status' => MediaStatus::Clean],
    ];

    /**
     * Mock-up drawn for an asset and its accent colour, by asset UUID.
     *
     * @var array<string, array{layout: string, accent: string}>
     */
    private const array MOCKUPS = [
        'a5000000-0000-4000-a000-000000000101' => ['layout' => 'hero', 'accent' => 'ea580c'],
        'a5000000-0000-4000-a000-000000000211' => ['layout' => 'website', 'accent' => 'c2410c'],
        'a5000000-0000-4000-a000-000000000212' => ['layout' => 'shop', 'accent' => 'd97706'],
        'a5000000-0000-4000-a000-000000000233' => ['layout' => 'dashboard', 'accent' => '334155'],
        'a5000000-0000-4000-a000-000000000214' => ['layout' => 'identity', 'accent' => 'fff7ed'],
        'a5000000-0000-4000-a000-000000000215' => ['layout' => 'booking', 'accent' => '4d7c0f'],
        'a5000000-0000-4000-a000-000000000216' => ['layout' => 'website', 'accent' => 'ea580c'],
    ];

    private const string INK = '1c1917';

    private const string LINE = 'd6d3d1';

    private const string PAPER = 'fbfaf8';

    private const string MUTED = 'f1eeea';

    protected function placeholder(array $definition, int $width, int $height): ImageInterface
    {
        $mockup = self::MOCKUPS[$definition['uuid']] ?? null;

        if ($mockup === null) {
            return parent::placeholder($definition, $width, $height);
        }

        $image = ImageManager::gd()->create($width, $height)->fill($definition['color'] ?? 'e9dfd1');
        $accent = $mockup['accent'];
        $this->dotGrid($image, $width, $height);

        match ($mockup['layout']) {
            'hero' => $this->hero($image, $width, $height, $accent),
            'identity' => $this->identity($image, $width, $height, $accent),
            default => $this->windowWith($mockup['layout'], $image, $width, $height, $accent),
        };

        return $image;
    }

    private function windowWith(string $layout, ImageInterface $image, int $width, int $height, string $accent): void
    {
        // Centred in the middle 56% of the width, so a tile that crops the
        // image to a square still shows the whole window.
        [$x, $y, $w, $h] = $this->window($image, (int) ($width * 0.22), (int) ($height * 0.14), (int) ($width * 0.56), (int) ($height * 0.86));

        match ($layout) {
            'shop' => $this->shop($image, $x, $y, $w, $h, $accent),
            'dashboard' => $this->dashboard($image, $x, $y, $w, $h, $accent),
            'booking' => $this->booking($image, $x, $y, $w, $h, $accent),
            default => $this->website($image, $x, $y, $w, $h, $accent),
        };
    }

    /**
     * Browser window with a soft shadow and a title bar; returns the content
     * box as [x, y, width, height].
     *
     * @return array{int, int, int, int}
     */
    private function window(ImageInterface $image, int $x, int $y, int $w, int $h): array
    {
        $this->rect($image, $x + 18, $y + 24, $w, $h, '0000001f');
        $this->rect($image, $x, $y, $w, $h, self::PAPER);
        $bar = max(28, intdiv($h, 16));
        $this->rect($image, $x, $y, $w, $bar, self::MUTED);

        foreach ([0, 1, 2] as $dot) {
            $this->dot($image, $x + $bar * (0.7 + $dot * 0.6), $y + $bar / 2, (int) ($bar * 0.36), self::LINE);
        }

        $pad = intdiv($w, 20);

        return [$x + $pad, $y + $bar + $pad, $w - 2 * $pad, $h - $bar - 2 * $pad];
    }

    private function website(ImageInterface $image, int $x, int $y, int $w, int $h, string $accent): void
    {
        $this->nav($image, $x, $y, $w, $accent);
        $top = $y + intdiv($h, 7);
        $this->rect($image, $x, $top, (int) ($w * 0.46), intdiv($h, 11), self::INK);
        $this->rect($image, $x, $top + intdiv($h, 8), (int) ($w * 0.34), intdiv($h, 11), self::INK);
        $this->rect($image, $x, $top + intdiv($h, 8) * 2 + 10, (int) ($w * 0.42), intdiv($h, 34), self::LINE);
        $this->rect($image, $x, $top + intdiv($h, 8) * 2 + 10 + intdiv($h, 22), (int) ($w * 0.36), intdiv($h, 34), self::LINE);
        $this->rect($image, $x, $top + (int) ($h * 0.42), (int) ($w * 0.16), intdiv($h, 12), $accent);
        $photoX = (int) ($x + $w * 0.56);
        $photoWidth = (int) ($w * 0.44);
        $photoHeight = (int) ($h * 0.5);
        $this->rect($image, $photoX, $top, $photoWidth, $photoHeight, $accent.'33');
        $this->dot($image, $photoX + $photoWidth * 0.72, $top + $photoHeight * 0.3, (int) ($photoHeight * 0.32), 'fde68a');
        $this->rect($image, $photoX, $top + (int) ($photoHeight * 0.62), $photoWidth, (int) ($photoHeight * 0.38), $accent.'80');
        $this->rect($image, $photoX + (int) ($photoWidth * 0.08), $top + (int) ($photoHeight * 0.48), (int) ($photoWidth * 0.3), (int) ($photoHeight * 0.52), $accent.'b3');
        $this->rect($image, $photoX + 18, $top + $photoHeight - 52, (int) ($photoWidth * 0.42), 34, self::PAPER);
        $this->dot($image, $photoX + 36, $top + $photoHeight - 35, 14, '16a34a');

        $cardTop = $y + (int) ($h * 0.74);
        $cardWidth = intdiv($w - 2 * intdiv($w, 30), 3);

        foreach ([0, 1, 2] as $card) {
            $cardX = $x + $card * ($cardWidth + intdiv($w, 30));
            $this->rect($image, $cardX, $cardTop, $cardWidth, $y + $h - $cardTop, self::MUTED);
            $this->rect($image, $cardX + 20, $cardTop + 22, (int) ($cardWidth * 0.5), intdiv($h, 30), self::INK.'cc');
            $this->rect($image, $cardX + 20, $cardTop + 22 + intdiv($h, 18), (int) ($cardWidth * 0.75), intdiv($h, 44), self::LINE);
        }
    }

    private function shop(ImageInterface $image, int $x, int $y, int $w, int $h, string $accent): void
    {
        $this->nav($image, $x, $y, $w, $accent);
        $top = $y + intdiv($h, 7);
        $gap = intdiv($w, 40);
        $tileWidth = intdiv($w - 3 * $gap, 4);
        $tones = ['e7d8c4', 'd6c3a9', 'efe4d6', 'cbb79c'];

        foreach ([0, 1] as $row) {
            foreach ([0, 1, 2, 3] as $column) {
                $tileX = $x + $column * ($tileWidth + $gap);
                $tileY = $top + $row * intdiv($h - intdiv($h, 7), 2);
                $tileHeight = (int) (intdiv($h - intdiv($h, 7), 2) * 0.62);
                $this->rect($image, $tileX, $tileY, $tileWidth, $tileHeight, $tones[($row + $column) % 4]);
                $this->dot($image, $tileX + $tileWidth / 2, $tileY + $tileHeight / 2, (int) ($tileHeight * 0.5), 'ffffff80');
                $this->rect($image, $tileX, $tileY + $tileHeight + 14, (int) ($tileWidth * 0.7), intdiv($h, 40), self::INK.'cc');
                $this->rect($image, $tileX, $tileY + $tileHeight + 14 + intdiv($h, 26), (int) ($tileWidth * 0.3), intdiv($h, 40), $accent);
            }
        }
    }

    private function dashboard(ImageInterface $image, int $x, int $y, int $w, int $h, string $accent): void
    {
        $side = (int) ($w * 0.18);
        $this->rect($image, $x, $y, $side, $h, self::MUTED);

        foreach (range(0, 5) as $item) {
            $this->rect($image, $x + 18, $y + 24 + $item * intdiv($h, 9), (int) ($side * ($item === 1 ? 0.7 : 0.55)), intdiv($h, 34), $item === 1 ? $accent : self::LINE);
        }

        $mainX = $x + $side + intdiv($w, 30);
        $mainWidth = $x + $w - $mainX;
        $kpiWidth = intdiv($mainWidth - 2 * intdiv($w, 40), 3);

        foreach ([0, 1, 2] as $kpi) {
            $kpiX = $mainX + $kpi * ($kpiWidth + intdiv($w, 40));
            $this->rect($image, $kpiX, $y, $kpiWidth, (int) ($h * 0.22), self::MUTED);
            $this->rect($image, $kpiX + 18, $y + 20, (int) ($kpiWidth * 0.4), intdiv($h, 40), self::LINE);
            $this->rect($image, $kpiX + 18, $y + 20 + intdiv($h, 18), (int) ($kpiWidth * 0.55), intdiv($h, 16), $kpi === 0 ? $accent : self::INK.'cc');
        }

        $chartTop = $y + (int) ($h * 0.3);
        $chartHeight = $y + $h - $chartTop;
        $this->rect($image, $mainX, $chartTop, $mainWidth, $chartHeight, self::MUTED);
        $bars = [0.35, 0.5, 0.42, 0.62, 0.55, 0.74, 0.66, 0.85, 0.78, 0.92];
        $barWidth = intdiv($mainWidth - 40, count($bars) * 2);

        $this->rect($image, $mainX + 24, $chartTop + 20, (int) ($mainWidth * 0.22), intdiv($h, 34), self::INK.'b3');
        $this->rect($image, $mainX + $mainWidth - (int) ($mainWidth * 0.16) - 24, $chartTop + 18, (int) ($mainWidth * 0.16), intdiv($h, 22), $accent.'26');

        foreach ($bars as $index => $ratio) {
            $barHeight = (int) (($chartHeight - 50) * $ratio);
            $this->rect($image, $mainX + 30 + $index * $barWidth * 2, $chartTop + $chartHeight - 24 - $barHeight, $barWidth, $barHeight, $index === count($bars) - 1 ? 'ea580c' : $accent.($index % 2 === 0 ? '99' : 'cc'));
        }
    }

    private function booking(ImageInterface $image, int $x, int $y, int $w, int $h, string $accent): void
    {
        $calendarWidth = (int) ($w * 0.62);
        $this->rect($image, $x, $y, (int) ($calendarWidth * 0.4), intdiv($h, 14), self::INK);
        $cellGap = 10;
        $cellWidth = intdiv($calendarWidth - 6 * $cellGap, 7);
        $gridTop = $y + intdiv($h, 6);
        $cellHeight = intdiv($y + $h - $gridTop - 4 * $cellGap, 5);

        foreach (range(0, 34) as $day) {
            $cellX = $x + ($day % 7) * ($cellWidth + $cellGap);
            $cellY = $gridTop + intdiv($day, 7) * ($cellHeight + $cellGap);
            $color = match (true) {
                $day === 17 => $accent,
                in_array($day, [9, 12, 22, 24, 30], true) => $accent.'40',
                default => self::MUTED,
            };
            $this->rect($image, $cellX, $cellY, $cellWidth, $cellHeight, $color);
        }

        $panelX = $x + $calendarWidth + intdiv($w, 24);
        $panelWidth = $x + $w - $panelX;
        $this->rect($image, $panelX, $y, $panelWidth, $h, self::MUTED);

        foreach (range(0, 4) as $slot) {
            $this->rect($image, $panelX + 24, $y + 30 + $slot * intdiv($h, 6), $panelWidth - 48, intdiv($h, 9), $slot === 1 ? $accent : self::PAPER);
        }
    }

    /** Brand sheet without a window, centred: logo mark, palette swatches and type. */
    private function identity(ImageInterface $image, int $width, int $height, string $accent): void
    {
        $this->dot($image, $width * 0.4, $height * 0.45, (int) ($height * 0.36), $accent);
        $this->dot($image, $width * 0.4, $height * 0.45, (int) ($height * 0.17), 'c2410c');
        $this->rect($image, (int) ($width * 0.52), (int) ($height * 0.3), (int) ($width * 0.2), intdiv($height, 12), $accent);
        $this->rect($image, (int) ($width * 0.52), (int) ($height * 0.44), (int) ($width * 0.14), intdiv($height, 28), $accent.'b3');

        foreach (['1c1917', 'fff7ed', 'fdba74', '7c2d12'] as $index => $swatch) {
            $this->rect($image, (int) ($width * 0.52) + $index * intdiv($width, 20), (int) ($height * 0.58), intdiv($width, 24), intdiv($width, 24), $swatch);
        }
    }

    /** App window with a phone card overlapping its corner. */
    private function hero(ImageInterface $image, int $width, int $height, string $accent): void
    {
        $this->dot($image, $width * 0.8, $height * 0.2, (int) ($height * 0.7), $accent.'38');
        [$x, $y, $w, $h] = $this->window($image, intdiv($width, 10), intdiv($height, 7), (int) ($width * 0.78), (int) ($height * 0.62));
        $this->dashboard($image, $x, $y, $w, $h, '57534e');

        $phoneWidth = (int) ($width * 0.24);
        $phoneHeight = (int) ($height * 0.5);
        $phoneX = (int) ($width * 0.68);
        $phoneY = (int) ($height * 0.44);
        $this->rect($image, $phoneX + 16, $phoneY + 22, $phoneWidth, $phoneHeight, '00000040');
        $this->rect($image, $phoneX, $phoneY, $phoneWidth, $phoneHeight, self::PAPER);
        $this->rect($image, $phoneX + 24, $phoneY + 36, (int) ($phoneWidth * 0.5), intdiv($height, 40), self::LINE);
        $this->rect($image, $phoneX + 24, $phoneY + 36 + intdiv($height, 22), (int) ($phoneWidth * 0.7), intdiv($height, 18), $accent);

        foreach (range(0, 3) as $row) {
            $rowY = $phoneY + (int) ($phoneHeight * 0.38) + $row * intdiv($phoneHeight, 7);
            $this->dot($image, $phoneX + 46, $rowY + 18, 30, $row === 0 ? $accent : self::LINE);
            $this->rect($image, $phoneX + 76, $rowY + 8, (int) ($phoneWidth * 0.55), intdiv($height, 50), self::INK.'b3');
        }
    }

    /** Faint dot grid on the backdrop, a quiet texture behind the window. */
    private function dotGrid(ImageInterface $image, int $width, int $height): void
    {
        $step = intdiv($width, 32);

        for ($y = intdiv($step, 2); $y < $height; $y += $step) {
            for ($x = intdiv($step, 2); $x < $width; $x += $step) {
                $this->dot($image, $x, $y, 4, 'ffffff2e');
            }
        }
    }

    private function nav(ImageInterface $image, int $x, int $y, int $w, string $accent): void
    {
        $this->rect($image, $x, $y, 34, 34, $accent);

        foreach ([0, 1, 2, 3] as $item) {
            $this->rect($image, (int) ($x + $w * 0.5) + $item * intdiv($w, 10), $y + 12, intdiv($w, 16), 12, self::LINE);
        }
    }

    private function rect(ImageInterface $image, int $x, int $y, int $width, int $height, string $color): void
    {
        $image->drawRectangle($x, $y, function (RectangleFactory $rectangle) use ($width, $height, $color): void {
            $rectangle->size(max(1, $width), max(1, $height));
            $rectangle->background($color);
        });
    }

    private function dot(ImageInterface $image, float $x, float $y, int $diameter, string $color): void
    {
        $image->drawEllipse((int) $x, (int) $y, function (EllipseFactory $ellipse) use ($diameter, $color): void {
            $ellipse->size($diameter, $diameter);
            $ellipse->background($color);
        });
    }
}
