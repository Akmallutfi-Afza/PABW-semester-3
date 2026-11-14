import { createInertiaApp } from '@inertiajs/react';
import type { ResolvedComponent } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Toaster } from '@/components/ui/sonner';
import { TooltipProvider } from '@/components/ui/tooltip';
import { initializeTheme } from '@/hooks/use-appearance';
import AuthLayout from '@/layouts/auth-layout';
import SettingsLayout from '@/layouts/settings/layout';
import AppLayout from '@/layouts/app-layout';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return null;
        }
    },
    resolve: async (name) => {
        const pages = import.meta.glob<{ default: ResolvedComponent }>([
            './pages/**/*.tsx',
            '../../Modules/*/resources/js/pages/**/*.tsx',
        ]);

        if (name.includes('::')) {
            const [module, page] = name.split('::');

            const loaded = await resolvePageComponent<{ default: ResolvedComponent }>(
                `../../Modules/${module}/resources/js/pages/${page}.tsx`,
                pages,
            );

            return loaded.default;
        }

        const loaded = await resolvePageComponent<{ default: ResolvedComponent }>(
            `./pages/${name}.tsx`,
            pages,
        );

        return loaded.default;
    },
    strictMode: true,
    withApp(app) {
        return (
            <TooltipProvider delayDuration={0}>
                {app}
                <Toaster />
            </TooltipProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
