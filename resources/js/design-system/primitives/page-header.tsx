import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { Heading } from './heading';
import { Inline } from './inline';
import { Stack } from './stack';
import { Text } from './text';

type PageHeaderAlign = 'start' | 'center';
type PageHeaderDensity = 'default' | 'compact';

export type PageHeaderProps = {
    title: ReactNode;
    description?: ReactNode;
    badge?: ReactNode;
    actions?: ReactNode;
    align?: PageHeaderAlign;
    /**
     * `compact` (panel screens): smaller title with the actions on the
     * same row at the end, so content starts higher.
     */
    density?: PageHeaderDensity;
    className?: never;
    style?: never;
};

export function PageHeader({
    title,
    description,
    badge,
    actions,
    align = 'start',
    density = 'default',
}: PageHeaderProps) {
    const isCenter = align === 'center';

    if (density === 'compact') {
        return (
            <div className="flex flex-col gap-3 pt-2 pb-2 sm:flex-row sm:items-end sm:justify-between sm:gap-6">
                <Stack gap="tight">
                    {badge && <div>{badge}</div>}
                    <Heading level={1} variant="section">
                        {title}
                    </Heading>
                    {description && <Text tone="muted">{description}</Text>}
                </Stack>
                {actions && (
                    <Inline gap="tight" align="center" wrap>
                        {actions}
                    </Inline>
                )}
            </div>
        );
    }

    return (
        <div
            className={cn(
                'flex flex-col gap-4 py-4 sm:py-6',
                isCenter ? 'items-center text-center' : 'items-start text-left',
            )}
        >
            {badge && <div>{badge}</div>}

            <Stack gap="tight" align={isCenter ? 'center' : 'start'}>
                <Heading level={1} variant="page" align={align}>
                    {title}
                </Heading>

                {description && (
                    <Text variant="lead" tone="muted" align={align}>
                        {description}
                    </Text>
                )}
            </Stack>

            {actions && (
                <div className="mt-2">
                    <Inline
                        gap="default"
                        align="stretch"
                        justify={isCenter ? 'center' : 'start'}
                        wrap
                    >
                        {actions}
                    </Inline>
                </div>
            )}
        </div>
    );
}
