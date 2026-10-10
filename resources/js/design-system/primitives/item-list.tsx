import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

export type ItemListItem = {
    id: string;
    /** Fixed-width start slot, e.g. a count badge or a status icon. */
    lead?: ReactNode;
    /** The row text; may hold inline extras such as a badge. */
    content: ReactNode;
    /** End slot that never wraps, e.g. a date or a small action. */
    end?: ReactNode;
};

export type ItemListProps = {
    items: ItemListItem[];
    className?: never;
    style?: never;
};

/**
 * Compact list of panel rows on one grid: a fixed start slot (centred
 * badge or icon), the text in small body type and an end column that never
 * wraps, so every row starts and ends on the same lines whatever the
 * width of its badge.
 */
export function ItemList({ items }: ItemListProps) {
    const hasLead = items.some((item) => item.lead !== undefined);

    return (
        <ul className="flex flex-col">
            {items.map((item) => (
                <li
                    key={item.id}
                    className={cn(
                        'border-border-subtle grid items-center gap-x-3 border-t py-2.5 text-sm first:border-t-0 first:pt-0 last:pb-0',
                        hasLead
                            ? 'grid-cols-[2.5rem_minmax(0,1fr)_auto]'
                            : 'grid-cols-[minmax(0,1fr)_auto]',
                    )}
                >
                    {hasLead && (
                        <span className="flex justify-center">{item.lead}</span>
                    )}
                    <span className="text-foreground flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1">
                        {item.content}
                    </span>
                    <span className="text-muted-foreground text-xs whitespace-nowrap tabular-nums">
                        {item.end}
                    </span>
                </li>
            ))}
        </ul>
    );
}
