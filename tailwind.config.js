/** @type {import('tailwindcss').Config} */
export const content = [
  "./*.php",
  "./template-parts/**/*.php",
  "./src/**/*.{js,css}",
  "./js/**/*.js",
];
export const theme = {
  extend: {
    colors: {
      gray4: 'var(--color-gray4)',
      gray8: 'var(--color-gray8)',
      gray11: 'var(--color-gray11)',
      gray12: 'var(--color-gray12)',
      paragraph: 'var(--color-paragraph)',
      primary: "#2563eb", // customize your brand color (blue-600)
      secondary: "#64748b", // grayish tone
      accent: "#f97316", // orange accent
    },
    fontFamily: {
      sans: ["Inter", "system-ui", "sans-serif"],
      heading: ["Poppins", "system-ui", "sans-serif"],
    },
    container: {
      center: true,
      padding: "1rem",
      screens: {
        sm: "600px",
        md: "728px",
        lg: "984px",
        xl: "1240px",
      },
    },
    fontSize: {
      'xxs': '0.725rem',
    },
  },
};
export const plugins = [
  require('@tailwindcss/typography'),
];
