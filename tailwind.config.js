module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      gridTemplateRows: {
        // Simple 8 row grid
        'modView': '100px 100px 1fr ',
        'mod': '100px 1fr ',
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
