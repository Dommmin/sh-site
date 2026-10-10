import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

type GridLayout =
    | 'single'
    | 'split'
    | 'split-asymmetric'
    | 'cards'
    | 'features'
    | 'bento';
type GridGap = 'tight' | 'default' | 'relaxed';
type GridAlign = 'start' | 'center';

export type GridProps = {
    children: ReactNode;
    layout?: GridLayout;
    gap?: GridGap;
    /**
     * `center` (cards layout only): cards keep their width and an incomplete
     * row is centred, for centred sections with one or two cards.
     */
    align?: GridAlign;
    className?: never;
    style?: never;
};

const layoutMap: Record<GridLayout, string> = {
    single: 'grid-cols-1',
    split: 'grid-cols-1 md:grid-cols-2',
    // Narrow lead column (5/12, e.g. a section heading) that stays in view
    // while the wide one (7/12) scrolls past it on large screens.
    'split-asymmetric':
        'grid-cols-1 lg:grid-cols-12 lg:[&>*:first-child]:sticky lg:[&>*:first-child]:top-24 lg:[&>*:first-child]:col-span-5 lg:[&>*:first-child]:self-start lg:[&>*:nth-child(2)]:col-span-7',
    cards: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    features: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    // Zig-zag on large screens: every row pairs a wide tile (two columns)
    // with a narrow one, alternating sides (see `isBentoWide`). DOM order
    // stays the visual reading order; tablets and phones use plain columns.
    bento: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:[&>*:nth-child(4n+1)]:col-span-2 lg:[&>*:nth-child(4n)]:col-span-2',
};

const gapMap: Record<GridGap, string> = {
    tight: 'gap-3 sm:gap-4',
    default: 'gap-6 sm:gap-8',
    relaxed: 'gap-8 sm:gap-12',
};

/**
 * Whether the item at `index` of a `bento` grid is a wide tile on large
 * screens, so its content can use the extra width (e.g. `ProjectTile wide`).
 */
export function isBentoWide(index: number): boolean {
    return index % 4 === 0 || index % 4 === 3;
}

export function Grid({
    children,
    layout = 'cards',
    gap = 'default',
    align = 'start',
}: GridProps) {
    const columns =
        align === 'center' && layout === 'cards'
            ? 'grid-cols-[repeat(auto-fit,minmax(min(100%,18rem),20rem))] justify-center'
            : layoutMap[layout];

    return (
        <div className={cn('grid w-full', columns, gapMap[gap])}>
            {children}
        </div>
    );
}
