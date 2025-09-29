import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                food: {
                    // Primary brand colors - Better contrast and visibility
                    primary: '#E97435',     // Deep orange - main brand color
                    'primary-dark': '#D35C20',  // Darker orange for hover states
                    'primary-light': '#F5A265', // Light orange for subtle backgrounds
                    
                    // Secondary colors
                    secondary: '#F59E0B',   // Amber yellow - warmer and more visible
                    'secondary-dark': '#D97706',
                    'secondary-light': '#FCD34D',
                    
                    // Accent colors
                    accent: '#059669',      // Emerald green - better contrast
                    'accent-dark': '#047857',
                    'accent-light': '#10B981',
                    
                    // Neutral colors - Better readability
                    dark: '#1F2937',        // Very dark gray - better text contrast
                    'dark-light': '#374151', // Medium dark gray
                    light: '#F9FAFB',       // Very light gray - clean backgrounds
                    'light-dark': '#E5E7EB', // Light gray for borders
                    
                    // Background colors
                    cream: '#FFFBEB',       // Warm cream - subtle background
                    beige: '#FEF7E0',       // Light beige - card backgrounds
                    
                    // Status colors - High contrast
                    success: '#065F46',     // Dark green - better contrast
                    'success-light': '#D1FAE5', // Light green background
                    warning: '#92400E',     // Dark amber - better contrast  
                    'warning-light': '#FEF3C7', // Light amber background
                    error: '#991B1B',       // Dark red - better contrast
                    'error-light': '#FEE2E2', // Light red background
                    
                    // Interactive colors
                    hover: '#F3F4F6',       // Light gray for hover states
                    border: '#D1D5DB',      // Medium gray for borders
                    'text-muted': '#6B7280', // Muted text color
                }
            },
            animation: {
                'bounce-slow': 'bounce 2s infinite',
                'pulse-slow': 'pulse 3s infinite',
            }
        },
    },

    plugins: [forms, typography],
};
