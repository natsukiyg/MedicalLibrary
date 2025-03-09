import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#000080',          // Navy
        primaryLight: 'rgba(0, 0, 128, 0.3)',  // Navy 30%
        primaryDark: 'rgba(0, 0, 128, 0.59)',   // Navy 59%
        greenCustom: '#008E14',      // 指定の緑
        pinkCustom: 'rgba(247,79,191,0.36)',  // ピンク 36%
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      fontSize: {
        '4xl': '2.25rem',  // 約36px
        '2xl': '1.5rem',   // 約24px
      },
    },
  },
  plugins: [forms],
}
