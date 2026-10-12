import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { Heading } from './heading';
import { Text } from './text';

export type NumberedListItem = {
    id: string;
    title?: string;
    description?: string;
    /** Decoration at the end of the row, e.g. an <Icon>. */
    trailing?: ReactNode;
};

type NumberedListSize = 'default' | 'compact';

export type NumberedListProps = {
    items: NumberedListItem[];
    /** Level of the item titles in the page outline. */
    headingLevel?: 2 | 3 | 4;
    /**
     * `default`: titled rows split by rules (services, process);
     * `compact`: short steps without rules.
     */
    size?: NumberedListSize;
    className?: never;
    style?: never;
};

/**
 * Ordered list with a two-digit counter (01, 02…) in its own column, set on
 * the baseline of the item's first line, so titles and descriptions share
 * one left edge. The counter is decorative: the `<ol>` already announces
 * the position.
 */
export function NumberedList({
    items,
    headingLevel = 3,
    size = 'default',
}: NumberedListProps) {
    const compact = size === 'compact';

    return (
        <ol className={cn('flex flex-col', compact ? 'gap-4' : 'gap-0')}>
            {items.map((item, index) => (
                <li
                    key={item.id}
                    className={cn(
                        'grid grid-cols-[2.25rem_minmax(0,1fr)_auto] items-baseline gap-x-3 sm:gap-x-4',
                        !compact &&
                            'border-border-subtle border-t py-6 first:border-t-0 first:pt-0 last:pb-0',
                    )}
                >
                    <span
                        aria-hidden="true"
                        className="text-primary text-sm font-medium tabular-nums"
                    >
                        {String(index + 1).padStart(2, '0')}
                    </span>
                    <div className="flex min-w-0 flex-col gap-2">
                        {item.title && (
                            <Heading
                                level={headingLevel}
                                variant={compact ? 'group' : 'subsection'}
                            >
                                {item.title}
                            </Heading>
                        )}
                        {item.description && (
                            <Text tone={item.title ? 'muted' : 'default'}>
                                {item.description}
                            </Text>
                        )}
                    </div>
                    {item.trailing ? (
                        <span className="self-start">{item.trailing}</span>
                    ) : (
                        <span />
                    )}
                </li>
            ))}
        </ol>
    );
}
