import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { Heading } from './heading';
import { Stack } from './stack';
import { Surface } from './surface';
import { Text } from './text';

export type ProjectTileProps = {
    /** Usually an <Image fit="cover">; the tile crops it to its frame. */
    media?: ReactNode;
    /** Category above the title. */
    eyebrow?: string;
    /** Title text or a <Link> to the project. */
    title: ReactNode;
    headingLevel?: 2 | 3;
    description?: string;
    /**
     * Wide tile of a `bento` grid: on large screens the media fills the
     * tile height beside the copy instead of sitting above it.
     */
    wide?: boolean;
    className?: never;
    style?: never;
};

/**
 * Portfolio project tile: a spotlight surface whose media zooms in slightly
 * on hover (motion-safe), with the category, title and an outcome.
 */
export function ProjectTile({
    media,
    eyebrow,
    title,
    headingLevel = 3,
    description,
    wide = false,
}: ProjectTileProps) {
    return (
        <Surface as="article" padding="compact" radius="lg" spotlight>
            <div
                className={cn(
                    'flex h-full flex-col gap-5',
                    wide &&
                        'lg:grid lg:grid-cols-[3fr_2fr] lg:items-stretch lg:gap-8',
                )}
            >
                {media && (
                    <span
                        className={cn(
                            'bg-surface-subtle block aspect-video w-full overflow-hidden rounded-lg',
                            '[&_img]:transition-transform [&_img]:duration-700 [&_img]:ease-[cubic-bezier(0.22,1,0.36,1)] motion-safe:group-hover/spotlight:[&_img]:scale-105',
                            wide && 'lg:aspect-auto lg:h-full lg:min-h-64',
                        )}
                    >
                        {media}
                    </span>
                )}
                <Stack gap="tight" justify={wide ? 'center' : 'start'}>
                    {eyebrow && (
                        <Text variant="label" tone="primary" as="p">
                            {eyebrow}
                        </Text>
                    )}
                    <Heading
                        level={headingLevel}
                        variant={wide ? 'subsection' : 'group'}
                    >
                        {title}
                    </Heading>
                    {description && <Text tone="muted">{description}</Text>}
                </Stack>
            </div>
        </Surface>
    );
}
