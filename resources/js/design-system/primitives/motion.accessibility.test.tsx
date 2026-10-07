import type { Page, PageProps, SharedPageProps } from '@inertiajs/core';
import { act, type ReactNode } from 'react';
import { createRoot, type Root } from 'react-dom/client';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { I18nProvider } from '@/i18n';
import { BackToTop } from './back-to-top';
import { Reveal } from './reveal';

vi.mock('@inertiajs/react', () => ({
    Link: ({ children }: { children: ReactNode }) => <a>{children}</a>,
    router: { on: () => () => {} },
}));

Object.assign(globalThis, { IS_REACT_ACT_ENVIRONMENT: true });

const initialPage = {
    props: {
        i18n: {
            locale: 'en',
            fallback: 'en',
            dir: 'ltr',
            messages: { a11y: { backToTop: 'Back to top' } },
        },
    },
} as unknown as Page<PageProps & SharedPageProps>;

const mountedRoots: Root[] = [];

async function render(node: ReactNode): Promise<HTMLElement> {
    const container = document.createElement('div');
    document.body.append(container);
    const root = createRoot(container);
    mountedRoots.push(root);

    await act(async () => {
        root.render(
            <I18nProvider initialPage={initialPage}>{node}</I18nProvider>,
        );
    });

    return container;
}

type ObserverCallback = (entries: { isIntersecting: boolean }[]) => void;
let observerCallbacks: ObserverCallback[] = [];

function installObserver() {
    observerCallbacks = [];
    vi.stubGlobal(
        'IntersectionObserver',
        class {
            constructor(callback: ObserverCallback) {
                observerCallbacks.push(callback);
            }
            observe() {}
            disconnect() {}
        },
    );
}

function setReducedMotion(reduce: boolean) {
    vi.stubGlobal('matchMedia', (query: string) => ({
        matches: reduce && query.includes('reduce'),
    }));
}

function placeBelowFold(top: number) {
    vi.spyOn(HTMLElement.prototype, 'getBoundingClientRect').mockReturnValue({
        top,
    } as DOMRect);
}

beforeEach(() => {
    setReducedMotion(false);
});

afterEach(async () => {
    await act(async () => {
        mountedRoots.splice(0).forEach((root) => root.unmount());
    });
    document.body.replaceChildren();
    vi.unstubAllGlobals();
    vi.restoreAllMocks();
    vi.useRealTimers();
});

describe('Reveal', () => {
    it('keeps content visible without IntersectionObserver (server HTML, no JS)', async () => {
        vi.stubGlobal('IntersectionObserver', undefined);
        const container = await render(
            <Reveal id="portfolio">
                <p>Projects</p>
            </Reveal>,
        );

        const block = container.querySelector('#portfolio');
        expect(block?.getAttribute('data-reveal')).toBe('static');
        expect(block?.className).not.toContain('opacity-0');
        expect(block?.textContent).toBe('Projects');
    });

    it('hides a block below the fold until it is reached, then settles', async () => {
        vi.useFakeTimers();
        installObserver();
        placeBelowFold(window.innerHeight + 200);
        const container = await render(
            <Reveal id="team">
                <p>People</p>
            </Reveal>,
        );
        const block = container.querySelector('#team');

        expect(block?.getAttribute('data-reveal')).toBe('hidden');
        expect(block?.className).toContain('opacity-0');

        await act(async () => {
            observerCallbacks[0]([{ isIntersecting: true }]);
        });
        expect(block?.getAttribute('data-reveal')).toBe('shown');
        expect(block?.className).not.toContain('opacity-0');

        await act(async () => {
            vi.advanceTimersByTime(1200);
        });
        expect(block?.getAttribute('data-reveal')).toBe('static');
        expect(block?.className).toBe('');
    });

    it('never animates a block on the first screen or with reduced motion', async () => {
        installObserver();
        placeBelowFold(10);
        const onScreen = await render(<Reveal id="first">First</Reveal>);
        expect(
            onScreen.querySelector('#first')?.getAttribute('data-reveal'),
        ).toBe('static');

        setReducedMotion(true);
        placeBelowFold(window.innerHeight + 200);
        const reduced = await render(<Reveal id="calm">Calm</Reveal>);
        expect(
            reduced.querySelector('#calm')?.getAttribute('data-reveal'),
        ).toBe('static');
        expect(observerCallbacks).toHaveLength(0);
    });
});

describe('BackToTop', () => {
    async function scrollTo(y: number) {
        Object.defineProperty(window, 'scrollY', {
            value: y,
            configurable: true,
        });
        await act(async () => {
            window.dispatchEvent(new Event('scroll'));
            await new Promise((resolve) => requestAnimationFrame(resolve));
        });
    }

    it('stays inert near the top and appears after scrolling far down', async () => {
        await scrollTo(0);
        const container = await render(<BackToTop />);
        const wrapper = container.firstElementChild as HTMLElement;
        const button = container.querySelector('button');

        expect(button?.getAttribute('aria-label')).toBe('Back to top');
        expect(wrapper.hasAttribute('inert')).toBe(true);

        await scrollTo(window.innerHeight * 2);
        expect(wrapper.hasAttribute('inert')).toBe(false);
    });

    it('scrolls up and moves focus to the start of the content', async () => {
        const main = document.createElement('main');
        main.id = 'main-content';
        document.body.append(main);
        const scroll = vi.fn();
        vi.stubGlobal('scrollTo', scroll);
        await scrollTo(window.innerHeight * 2);
        const container = await render(<BackToTop />);

        await act(async () => {
            container.querySelector('button')?.click();
        });

        expect(scroll).toHaveBeenCalledWith({ top: 0, behavior: 'smooth' });
        expect(document.activeElement).toBe(main);
        expect(main.getAttribute('tabindex')).toBe('-1');
    });
});
