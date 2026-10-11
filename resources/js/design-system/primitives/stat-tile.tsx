import type { RouteDefinition } from '@/wayfinder';
import { Link as InertiaLink } from '@inertiajs/react';
import { ArrowDownRight, ArrowRight, ArrowUpRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Icon } from './icon';

export type StatTileDelta = {
    /** Signed change against the comparison period, e.g. `3` or `-2`. */
    value: number;
    /** What the change is measured against, e.g. "vs previous 7 days". */
    label: string;
    /** Which direction is good news; `neutral` never colours the change. */
    goodWhen?: 'up' | 'down' | 'neutral';
};

export type StatTileProps = {
    /** Sentence-case name of the figure, e.g. "Messages in 7 days". */
    label: string;
    /** Formatted figure, e.g. `12` or `1.2K`. */
    value: string;
    delta?: StatTileDelta;
    /** One short line of context under the value, e.g. the next date. */
    hint?: string;
    /** Opens the list behind the figure; the whole tile is the link. */
    href?: RouteDefinition<'get'> | string;
    /** Full accessible name of the link, e.g. "Messages in 7 days: 12". */
    linkLabel?: string;
    className?: never;
    style?: never;
};

const toneClasses = {
    good: 'text-status-success',
    bad: 'text-destructive',
    neutral: 'text-muted-foreground',
} as const;

const iconTones = {
    good: 'success',
    bad: 'danger',
    neutral: 'muted',
} as const;

function deltaTone(delta: StatTileDelta): keyof typeof toneClasses {
    if (delta.value === 0 || (delta.goodWhen ?? 'up') === 'neutral') {
        return 'neutral';
    }

    const isUp = delta.value > 0;

    return isUp === ((delta.goodWhen ?? 'up') === 'up') ? 'good' : 'bad';
}

/**
 * Key figure of a dashboard: label, a large value and an optional signed
 * change with an arrow and words (never colour alone). With `href` the
 * tile opens the list behind the number.
 */
export function StatTile({
    label,
    value,
    delta,
    hint,
    href,
    linkLabel,
}: StatTileProps) {
    const body = (
        <>
            <span className="text-muted-foreground text-sm font-medium">
                {label}
            </span>
            <span className="text-foreground text-3xl font-semibold tracking-tight">
                {value}
            </span>
            {delta && (
                <span
                    className={cn(
                        'inline-flex items-center gap-1 text-xs font-medium',
                        toneClasses[deltaTone(delta)],
                    )}
                >
                    <Icon
                        icon={
                            delta.value > 0
                                ? ArrowUpRight
                                : delta.value < 0
                                  ? ArrowDownRight
                                  : ArrowRight
                        }
                        size="sm"
                        tone={iconTones[deltaTone(delta)]}
                    />
                    {delta.value > 0 ? `+${delta.value}` : delta.value}{' '}
                    <span className="text-muted-foreground font-normal">
                        {delta.label}
                    </span>
                </span>
            )}
            {hint && (
                <span className="text-muted-foreground text-xs">{hint}</span>
            )}
        </>
    );
    const classes =
        'bg-card text-card-foreground border-border-subtle flex min-w-0 flex-col gap-1.5 rounded-lg border p-4 sm:p-5';

    if (href) {
        return (
            <InertiaLink
                href={typeof href === 'string' ? href : href.url}
                aria-label={linkLabel}
                className={cn(
                    classes,
                    'hover:border-primary/40 focus-visible:ring-ring transition-colors duration-150 hover:shadow-sm focus-visible:ring-2 focus-visible:outline-none',
                )}
            >
                {body}
            </InertiaLink>
        );
    }

    return <div className={classes}>{body}</div>;
}
