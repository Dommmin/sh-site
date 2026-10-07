import { cn } from '@/lib/utils';

export type StatListItem = {
    id: string;
    /** Display text of the figure, e.g. `300+` or `98%`. */
    value: string;
    /** What the figure counts, e.g. "projects delivered". */
    label: string;
};

type StatListAlign = 'start' | 'center';

export type StatListProps = {
    items: StatListItem[];
    align?: StatListAlign;
    className?: never;
    style?: never;
};

/**
 * Key figures as a description list: a large value above its label, two
 * columns on phones and up to four on wide screens. Values are plain text,
 * so they are never headings and keep the page outline intact.
 */
export function StatList({ items, align = 'center' }: StatListProps) {
    if (items.length === 0) {
        return null;
    }

    return (
        <dl
            className={cn(
                'grid grid-cols-2 gap-x-6 gap-y-8',
                items.length >= 3 && 'lg:grid-cols-3',
                items.length >= 4 && 'lg:grid-cols-4',
            )}
        >
            {items.map((item) => (
                <div
                    key={item.id}
                    className={cn(
                        'flex min-w-0 flex-col-reverse gap-1',
                        align === 'center' && 'items-center text-center',
                    )}
                >
                    <dt className="text-muted-foreground text-sm font-medium wrap-anywhere">
                        {item.label}
                    </dt>
                    <dd className="text-primary font-serif text-3xl font-bold tracking-tight sm:text-4xl">
                        {item.value}
                    </dd>
                </div>
            ))}
        </dl>
    );
}
