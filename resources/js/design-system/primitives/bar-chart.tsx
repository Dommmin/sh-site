import { useId, useState, type KeyboardEvent, type ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { Table2 } from 'lucide-react';
import { Heading } from './heading';
import { Icon } from './icon';

type BarChartTone = 'chart-1' | 'chart-2';

export type BarChartSeries = {
    id: string;
    label: string;
    tone: BarChartTone;
    /**
     * Hatched fill: a second channel besides hue, for the series that
     * calls for attention (and for colour-blind or print readers).
     */
    hatched?: boolean;
};

export type BarChartDatum = {
    key: string;
    /** Short axis label, e.g. "3.10". */
    label: string;
    /** Full label for the tooltip and the table, e.g. "3 October 2026". */
    fullLabel: string;
    values: Record<string, number>;
};

export type BarChartProps = {
    title: string;
    /** One-sentence summary read by screen readers, e.g. totals and peak. */
    summary: string;
    series: BarChartSeries[];
    data: BarChartDatum[];
    labels: {
        showTable: string;
        hideTable: string;
        /** Header of the first table column, e.g. "Day". */
        category: string;
        total: string;
    };
    /** Shown instead of the plot when every value is zero. */
    empty?: ReactNode;
    /** On phones only the last `compactCount` bars are drawn. */
    compactCount?: number;
    formatValue?: (value: number) => string;
    className?: never;
    style?: never;
};

const fillClasses: Record<BarChartTone, string> = {
    'chart-1': 'bg-chart-1',
    'chart-2': 'bg-chart-2',
};

const hatch =
    'bg-[repeating-linear-gradient(135deg,transparent_0_3px,color-mix(in_oklch,var(--card)_55%,transparent)_3px_6px)]';

/** A round axis maximum (1, 2, 5 × 10ⁿ) at or above `max`. */
function niceMax(max: number): number {
    if (max <= 4) {
        return Math.max(max, 1);
    }

    const magnitude = 10 ** Math.floor(Math.log10(max));

    return (
        [1, 2, 2.5, 4, 5, 6, 8, 10]
            .map((step) => step * magnitude)
            .find((value) => value >= max) ?? max
    );
}

/**
 * Stacked column chart of a short time series (e.g. messages per day):
 * thin columns from one baseline with a surface gap between segments,
 * recessive gridlines, a legend for two or more series, a tooltip for
 * the pointer and for keyboard focus (arrow keys move between columns),
 * and a table view with every value. The plot is an image with a written
 * summary for screen readers; the table is the accessible twin.
 */
export function BarChart({
    title,
    summary,
    series,
    data,
    labels,
    empty,
    compactCount,
    formatValue = (value) => String(value),
}: BarChartProps) {
    const id = useId();
    const [active, setActive] = useState<number | null>(null);
    const [showTable, setShowTable] = useState(false);
    const totals = data.map((datum) =>
        series.reduce((sum, item) => sum + (datum.values[item.id] ?? 0), 0),
    );
    const max = niceMax(Math.max(0, ...totals));
    const ticks = [max, max / 2, 0];
    const isEmpty = totals.every((total) => total === 0);
    const firstCompact =
        compactCount === undefined ? 0 : data.length - compactCount;

    function onKeyDown(event: KeyboardEvent<HTMLDivElement>) {
        if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') {
            return;
        }

        event.preventDefault();
        const step = event.key === 'ArrowRight' ? 1 : -1;
        setActive((current) =>
            Math.min(
                data.length - 1,
                Math.max(0, (current ?? data.length) + step),
            ),
        );
    }

    return (
        <figure
            className="flex min-w-0 flex-col gap-4"
            aria-labelledby={`${id}-title`}
        >
            <div className="flex flex-col gap-2">
                <div className="flex items-center justify-between gap-3">
                    <Heading level={2} variant="group">
                        <span id={`${id}-title`}>{title}</span>
                    </Heading>
                    <button
                        type="button"
                        onClick={() => setShowTable((value) => !value)}
                        aria-expanded={showTable}
                        aria-controls={`${id}-table`}
                        className="text-foreground hover:bg-surface-subtle focus-visible:ring-ring border-border-subtle aria-expanded:bg-surface-subtle inline-flex min-h-9 shrink-0 items-center gap-1.5 rounded-md border px-3 text-sm font-medium transition-colors duration-150 focus-visible:ring-2 focus-visible:outline-none"
                    >
                        <Icon icon={Table2} size="sm" tone="muted" />
                        {showTable ? labels.hideTable : labels.showTable}
                    </button>
                </div>
                {series.length > 1 && (
                    <ul className="text-muted-foreground flex flex-wrap gap-x-4 gap-y-1 text-xs">
                        {series.map((item) => (
                            <li
                                key={item.id}
                                className="inline-flex items-center gap-1.5"
                            >
                                <span
                                    aria-hidden="true"
                                    className={cn(
                                        'size-2.5 rounded-sm',
                                        fillClasses[item.tone],
                                        item.hatched && hatch,
                                    )}
                                />
                                {item.label}
                            </li>
                        ))}
                    </ul>
                )}
            </div>

            {isEmpty && empty ? (
                <div className="text-muted-foreground border-border-subtle rounded-lg border border-dashed p-6 text-sm">
                    {empty}
                </div>
            ) : (
                <div className="grid grid-cols-[auto_minmax(0,1fr)] gap-x-3">
                    <div
                        aria-hidden="true"
                        className="text-muted-foreground flex h-44 flex-col justify-between text-right text-xs tabular-nums"
                    >
                        {ticks.map((tick) => (
                            <span
                                key={tick}
                                className="-translate-y-1/2 first:translate-y-0 last:translate-y-0"
                            >
                                {formatValue(tick)}
                            </span>
                        ))}
                    </div>
                    <div
                        role="img"
                        aria-label={`${title}. ${summary}`}
                        tabIndex={0}
                        onKeyDown={onKeyDown}
                        onBlur={() => setActive(null)}
                        onPointerLeave={() => setActive(null)}
                        className="focus-visible:ring-ring relative h-44 rounded-sm focus-visible:ring-2 focus-visible:ring-offset-4 focus-visible:outline-none"
                    >
                        {ticks.map((tick) => (
                            <span
                                key={tick}
                                aria-hidden="true"
                                className="border-border-subtle absolute inset-x-0 border-t"
                                style={{ top: `${100 - (tick / max) * 100}%` }}
                            />
                        ))}
                        <div className="relative flex h-full items-end gap-[2px]">
                            {data.map((datum, index) => (
                                <div
                                    key={datum.key}
                                    onPointerEnter={() => setActive(index)}
                                    className={cn(
                                        'group/bar flex h-full min-w-0 flex-1 flex-col items-center justify-end',
                                        index < firstCompact && 'max-sm:hidden',
                                    )}
                                >
                                    <div
                                        className={cn(
                                            'flex w-full max-w-6 flex-col-reverse gap-[2px] transition-opacity duration-150',
                                            active !== null &&
                                                active !== index &&
                                                'opacity-50',
                                        )}
                                        style={{
                                            height: `${(totals[index] / max) * 100}%`,
                                        }}
                                    >
                                        {series.map((item, seriesIndex) => {
                                            const value =
                                                datum.values[item.id] ?? 0;

                                            if (value === 0) {
                                                return null;
                                            }

                                            const isTop = series
                                                .slice(seriesIndex + 1)
                                                .every(
                                                    (next) =>
                                                        (datum.values[
                                                            next.id
                                                        ] ?? 0) === 0,
                                                );

                                            return (
                                                <span
                                                    key={item.id}
                                                    className={cn(
                                                        'block w-full',
                                                        fillClasses[item.tone],
                                                        item.hatched && hatch,
                                                        isTop &&
                                                            'rounded-t-[4px]',
                                                    )}
                                                    style={{
                                                        flexGrow: value,
                                                    }}
                                                />
                                            );
                                        })}
                                    </div>
                                </div>
                            ))}
                        </div>
                        {active !== null && data[active] && (
                            <div
                                aria-hidden="true"
                                className="bg-popover text-popover-foreground border-border-subtle pointer-events-none absolute top-0 z-10 flex min-w-36 -translate-x-1/2 flex-col gap-1 rounded-md border px-3 py-2 text-xs shadow-md"
                                style={{
                                    left: `clamp(4.5rem, ${((active + 0.5) / data.length) * 100}%, calc(100% - 4.5rem))`,
                                }}
                            >
                                <span className="text-muted-foreground">
                                    {data[active].fullLabel}
                                </span>
                                {series.map((item) => (
                                    <span
                                        key={item.id}
                                        className="flex items-center justify-between gap-4"
                                    >
                                        <span className="inline-flex items-center gap-1.5">
                                            <span
                                                className={cn(
                                                    'h-0.5 w-3 rounded-full',
                                                    fillClasses[item.tone],
                                                )}
                                            />
                                            {item.label}
                                        </span>
                                        <strong className="font-semibold tabular-nums">
                                            {formatValue(
                                                data[active].values[item.id] ??
                                                    0,
                                            )}
                                        </strong>
                                    </span>
                                ))}
                            </div>
                        )}
                    </div>
                    <span />
                    <div
                        aria-hidden="true"
                        className="text-muted-foreground mt-2 flex gap-[2px] text-xs"
                    >
                        {data.map((datum, index) => {
                            const fromEnd = data.length - 1 - index;

                            return (
                                <span
                                    key={datum.key}
                                    className={cn(
                                        'min-w-0 flex-1 overflow-visible text-center whitespace-nowrap',
                                        index < firstCompact && 'max-sm:hidden',
                                        fromEnd % 7 !== 0 && 'invisible',
                                    )}
                                >
                                    {datum.label}
                                </span>
                            );
                        })}
                    </div>
                </div>
            )}

            <div id={`${id}-table`} hidden={!showTable}>
                <table className="w-full text-left text-sm">
                    <caption className="sr-only">{title}</caption>
                    <thead className="text-muted-foreground text-xs">
                        <tr>
                            <th scope="col" className="py-1.5 font-medium">
                                {labels.category}
                            </th>
                            {series.map((item) => (
                                <th
                                    key={item.id}
                                    scope="col"
                                    className="py-1.5 text-right font-medium"
                                >
                                    {item.label}
                                </th>
                            ))}
                            <th
                                scope="col"
                                className="py-1.5 text-right font-medium"
                            >
                                {labels.total}
                            </th>
                        </tr>
                    </thead>
                    <tbody className="tabular-nums">
                        {data.map((datum, index) => (
                            <tr
                                key={datum.key}
                                className="border-border-subtle border-t"
                            >
                                <th scope="row" className="py-1.5 font-normal">
                                    {datum.fullLabel}
                                </th>
                                {series.map((item) => (
                                    <td
                                        key={item.id}
                                        className="py-1.5 text-right"
                                    >
                                        {formatValue(
                                            datum.values[item.id] ?? 0,
                                        )}
                                    </td>
                                ))}
                                <td className="py-1.5 text-right font-medium">
                                    {formatValue(totals[index])}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </figure>
    );
}
