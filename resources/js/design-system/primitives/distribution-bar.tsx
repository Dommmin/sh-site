import { cn } from '@/lib/utils';

type DistributionTone = 'chart-1' | 'chart-2' | 'neutral';

export type DistributionSegment = {
    id: string;
    label: string;
    value: number;
    tone: DistributionTone;
};

export type DistributionBarProps = {
    /** What is split, e.g. "Articles"; also the accessible name. */
    label: string;
    segments: DistributionSegment[];
    /** Shown instead of the bar when every segment is zero. */
    emptyLabel: string;
    formatValue?: (value: number) => string;
    className?: never;
    style?: never;
};

const fillClasses: Record<DistributionTone, string> = {
    'chart-1': 'bg-chart-1',
    'chart-2': 'bg-chart-2',
    neutral: 'bg-muted-foreground/35',
};

/**
 * Part-to-whole of one small set (e.g. published, scheduled and draft
 * articles) as a single horizontal bar with surface gaps between the
 * parts, followed by a legend list with every count, so no value depends
 * on colour or on hovering.
 */
export function DistributionBar({
    label,
    segments,
    emptyLabel,
    formatValue = (value) => String(value),
}: DistributionBarProps) {
    const total = segments.reduce((sum, segment) => sum + segment.value, 0);

    return (
        <div className="flex min-w-0 flex-col gap-2">
            <div className="flex items-baseline justify-between gap-3 text-sm">
                <span className="text-foreground font-medium">{label}</span>
                <span className="text-muted-foreground tabular-nums">
                    {formatValue(total)}
                </span>
            </div>
            {total === 0 ? (
                <p className="text-muted-foreground text-sm">{emptyLabel}</p>
            ) : (
                <div
                    aria-hidden="true"
                    className="flex h-2.5 w-full gap-[2px] overflow-hidden rounded-full"
                >
                    {segments
                        .filter((segment) => segment.value > 0)
                        .map((segment) => (
                            <span
                                key={segment.id}
                                className={cn(
                                    'h-full',
                                    fillClasses[segment.tone],
                                )}
                                style={{ flexGrow: segment.value }}
                            />
                        ))}
                </div>
            )}
            <ul className="text-muted-foreground flex flex-wrap gap-x-4 gap-y-1 text-xs">
                {segments.map((segment) => (
                    <li
                        key={segment.id}
                        className="inline-flex items-center gap-1.5"
                    >
                        <span
                            aria-hidden="true"
                            className={cn(
                                'size-2 rounded-full',
                                fillClasses[segment.tone],
                            )}
                        />
                        {segment.label}
                        <span className="text-foreground font-medium tabular-nums">
                            {formatValue(segment.value)}
                        </span>
                    </li>
                ))}
            </ul>
        </div>
    );
}
