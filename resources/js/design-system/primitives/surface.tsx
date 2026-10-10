import type { PointerEvent, ReactNode } from 'react';
import { cn } from '@/lib/utils';

type SurfaceTone = 'default' | 'subtle' | 'raised' | 'inverted';
type SurfacePadding = 'none' | 'compact' | 'default' | 'relaxed';
type SurfaceRadius = 'none' | 'sm' | 'default' | 'lg' | 'full';

export type SurfaceProps = {
    children: ReactNode;
    tone?: SurfaceTone;
    padding?: SurfacePadding;
    radius?: SurfaceRadius;
    border?: boolean;
    as?: 'div' | 'article' | 'aside' | 'section';
    /**
     * Interactive tile (e.g. a portfolio project): lifts on hover and a soft
     * accent glow follows a mouse pointer. Touch and reduced motion keep a
     * still border highlight only.
     */
    spotlight?: boolean;
    className?: never;
    style?: never;
};

const toneMap: Record<SurfaceTone, string> = {
    default: 'bg-card text-card-foreground',
    subtle: 'bg-surface-subtle text-foreground',
    raised: 'bg-surface-raised text-foreground shadow-md',
    inverted: 'bg-surface-emphasis text-surface-emphasis-foreground shadow-xl',
};

const paddingMap: Record<SurfacePadding, string> = {
    none: 'p-0',
    compact: 'p-4 sm:p-5',
    default: 'p-6 sm:p-8',
    relaxed: 'p-8 sm:p-12',
};

const radiusMap: Record<SurfaceRadius, string> = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    default: 'rounded-lg',
    lg: 'rounded-2xl',
    full: 'rounded-full',
};

const spotlightClasses = cn(
    'group/spotlight transition-[translate,box-shadow,border-color] duration-300 ease-[cubic-bezier(0.22,1,0.36,1)]',
    'hover:border-primary/40 hover:shadow-lg motion-safe:hover:-translate-y-1',
    'focus-within:border-primary/40',
);

function followPointer(event: PointerEvent<HTMLElement>) {
    if (event.pointerType !== 'mouse') {
        return;
    }

    const box = event.currentTarget.getBoundingClientRect();
    event.currentTarget.style.setProperty(
        '--spot-x',
        `${event.clientX - box.left}px`,
    );
    event.currentTarget.style.setProperty(
        '--spot-y',
        `${event.clientY - box.top}px`,
    );
}

export function Surface({
    children,
    tone = 'default',
    padding = 'default',
    radius = 'default',
    border = true,
    as: Component = 'div',
    spotlight = false,
}: SurfaceProps) {
    return (
        <Component
            onPointerMove={spotlight ? followPointer : undefined}
            className={cn(
                'relative overflow-hidden',
                toneMap[tone],
                paddingMap[padding],
                radiusMap[radius],
                border && 'border-border-subtle border',
                spotlight && spotlightClasses,
            )}
        >
            {spotlight && (
                <span
                    aria-hidden="true"
                    className="pointer-events-none absolute inset-0 z-0 bg-[radial-gradient(28rem_circle_at_var(--spot-x,50%)_var(--spot-y,0%),color-mix(in_oklch,var(--primary)_14%,transparent),transparent_70%)] opacity-0 transition-opacity duration-300 motion-safe:group-hover/spotlight:opacity-100"
                />
            )}
            {spotlight ? (
                <div className="relative z-10 h-full">{children}</div>
            ) : (
                children
            )}
        </Component>
    );
}
