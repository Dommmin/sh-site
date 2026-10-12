import type { PointerEvent, ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { Heading } from './heading';
import { Section } from './section';
import { Stack } from './stack';
import { Text } from './text';

type HeroAlign = 'start' | 'center';

export type HeroProps = {
    eyebrow?: string;
    title: string;
    description?: string;
    /** Typically one or two <Button>s. */
    actions?: ReactNode;
    /** Proof under the actions, e.g. a <StatList> of key figures. */
    proof?: ReactNode;
    /** Optional visual, e.g. an <img>, rendered beside the copy on wide screens. */
    media?: ReactNode;
    /**
     * Give the media depth: a soft brand glow behind it and a gentle 3D tilt
     * that follows a mouse pointer. Touch and reduced motion keep it still.
     */
    mediaDepth?: boolean;
    align?: HeroAlign;
};

const TILT_DEG = 6;

function tilt(event: PointerEvent<HTMLElement>) {
    if (event.pointerType !== 'mouse') {
        return;
    }

    const box = event.currentTarget.getBoundingClientRect();
    const x = (event.clientX - box.left) / box.width - 0.5;
    const y = (event.clientY - box.top) / box.height - 0.5;
    event.currentTarget.style.setProperty('--tilt-x', `${x * TILT_DEG}deg`);
    event.currentTarget.style.setProperty('--tilt-y', `${-y * TILT_DEG}deg`);
}

function resetTilt(event: PointerEvent<HTMLElement>) {
    event.currentTarget.style.removeProperty('--tilt-x');
    event.currentTarget.style.removeProperty('--tilt-y');
}

/**
 * Page opening. The copy and the media enter with a short slide and zoom
 * (transform only, so the server-rendered content is never hidden and
 * LCP is not delayed); reduced motion shows them in place.
 */
export function Hero({
    eyebrow,
    title,
    description,
    actions,
    proof,
    media,
    mediaDepth = false,
    align = 'start',
}: HeroProps) {
    const copy = (
        <Stack gap="default" align={align === 'center' ? 'center' : 'start'}>
            {eyebrow && (
                <Text variant="label" tone="primary" align={align}>
                    {eyebrow}
                </Text>
            )}
            <Heading level={1} variant="display" align={align}>
                {title}
            </Heading>
            {description && (
                <Text variant="lead" tone="muted" align={align}>
                    {description}
                </Text>
            )}
            {actions && (
                <div
                    className={cn(
                        'flex flex-col gap-3 sm:flex-row',
                        align === 'center' && 'sm:justify-center',
                    )}
                >
                    {actions}
                </div>
            )}
            {proof && <div className="w-full pt-6 sm:pt-8">{proof}</div>}
        </Stack>
    );

    return (
        <Section spacing="relaxed">
            {media ? (
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-2 lg:gap-16">
                    <div className="motion-safe:animate-in motion-safe:slide-in-from-bottom-6 motion-safe:duration-700 motion-safe:ease-[cubic-bezier(0.22,1,0.36,1)]">
                        {copy}
                    </div>
                    {mediaDepth ? (
                        <div
                            className="relative isolate [perspective:1200px]"
                            onPointerMove={tilt}
                            onPointerLeave={resetTilt}
                        >
                            <span
                                aria-hidden="true"
                                className="bg-brand-glow motion-safe:animate-in motion-safe:fade-in absolute -inset-6 -z-10 rounded-[3rem] opacity-90 blur-3xl motion-safe:duration-1000 sm:-inset-10"
                            />
                            <div className="motion-safe:animate-in motion-safe:zoom-in-95 motion-safe:slide-in-from-bottom-4 motion-safe:duration-1000 motion-safe:ease-[cubic-bezier(0.22,1,0.36,1)]">
                                <div className="transition-transform duration-300 ease-out motion-safe:[transform:rotateX(var(--tilt-y,0deg))_rotateY(var(--tilt-x,0deg))]">
                                    {media}
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div>{media}</div>
                    )}
                </div>
            ) : (
                <div
                    className={cn(
                        'mx-auto max-w-3xl',
                        align === 'center' && 'text-center',
                    )}
                >
                    {copy}
                </div>
            )}
        </Section>
    );
}
