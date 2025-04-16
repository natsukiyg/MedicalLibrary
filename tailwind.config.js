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
        medical: {
          base: '#C2CBD7',     // ブルーグレー（背景/コンテナ）
          accent: '#A398D2',   // ラベンダー（ボタン/アクセント）
          neutral: '#4E5975',  // ソフトネイビー（見出し/強調）
        },
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