import { usePage } from '@inertiajs/react';
import { useTranslation } from '@/i18n';
import type { BrandLogoImage } from './brand-logo';

/*
 * Internal to the design system (not re-exported from the barrel): the
 * public header, footer and auth header resolve the brand the same way.
 */

export type SiteBrand = {
    /** Uploaded site logo with the site name as its accessible name. */
    logo?: BrandLogoImage;
    /** Saved site name; undefined keeps the two-tone catalog text logo. */
    brandName?: string;
};

/**
 * Brand of the shared `site` settings. The saved name replaces the catalog
 * text logo only once the settings were saved (`site.isCustomized`) and
 * only when it differs from the catalog name, which keeps its two tones.
 */
export function useSiteBrand(): SiteBrand {
    const { t } = useTranslation();
    const site = usePage().props.site as
        | App.Data.Settings.SiteSettingsData
        | undefined;
    const isOwnName = site?.isCustomized && site.name !== t('brand.name');

    return {
        logo: site?.logo ? { ...site.logo, alt: site.name } : undefined,
        brandName: isOwnName ? site.name : undefined,
    };
}
