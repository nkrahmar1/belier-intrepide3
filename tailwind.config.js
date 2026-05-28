/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  /* Réactivation de Preflight : Tailwind sera la base principale de styles.
     Si vous préférez que Bootstrap reste la base, remettre preflight: false.
  */
  // corePlugins: { preflight: false },
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
