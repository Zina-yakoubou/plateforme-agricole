/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.{vue,js,ts,jsx,tsx}",
    "./storage/framework/views/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#006a4f',
          dark: '#156c52',
          hover: '#005642',
        },
        accent: {
          yellow: '#ffcf2f',
          red: '#ab1717',
        },
        background: {
          DEFAULT: '#ffffff',
          muted: '#e5f2ee',
          dark: '#000000',
        },
        text: {
          primary: '#212529',
          secondary: '#434343',
          muted: '#374151',
          inverse: '#ffffff',
        },
        border: {
          DEFAULT: '#e5e7eb',
        },
      },

      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
        mono: [
          'SFMono-Regular',
          'Menlo',
          'Monaco',
          'Consolas',
          '"Liberation Mono"',
          '"Courier New"',
          'monospace',
        ],
      },

      fontSize: {
        display: ['60px', { lineHeight: '1.2', fontWeight: '600' }],
        'heading-1': ['40px', { lineHeight: '1.2', fontWeight: '600' }],
        'heading-2': ['24px', { lineHeight: '1.2', fontWeight: '600' }],
        'heading-3': ['20px', { lineHeight: '1.5', fontWeight: '400' }],
        body: ['16px', { lineHeight: '1.5', fontWeight: '400' }],
        'body-sm': ['14px', { lineHeight: '1.5', fontWeight: '400' }],
        caption: ['12px', { lineHeight: '1.5', fontWeight: '400' }],
        code: ['14px', { lineHeight: '1.5', fontWeight: '400' }],
      },

      spacing: {
        1: '4px',
        2: '8px',
        3: '12px',
        4: '16px',
        5: '20px',
        6: '24px',
        8: '32px',
        10: '40px',
        12: '48px',
        16: '64px',
        20: '80px',
        24: '96px',
      },

      borderRadius: {
        sm: '6px',
        DEFAULT: '8px',
        md: '8px',
        lg: '16px',
        full: '9999px',
      },

      boxShadow: {
        card: '0 1px 2px rgba(0,0,0,0.05)',
        popover: '0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.1)',
        modal: '0 25px 50px rgba(0,0,0,0.25)',
      },

      transitionDuration: {
        base: '150ms',
        slow: '350ms',
      },

      transitionTimingFunction: {
        standard: 'ease-in-out',
        linear: 'linear',
      },
    },
  },
  plugins: [],
}