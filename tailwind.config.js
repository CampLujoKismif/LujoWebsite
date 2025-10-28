/** @type {import('tailwindcss').Config} */
export default {
  darkMode: ['selector', 'body.dark'], // Flux adds .dark to body element
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

