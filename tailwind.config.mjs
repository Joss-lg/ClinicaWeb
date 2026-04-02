/** @type {import('tailwindcss').Config} */
export default {
	content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
	theme: {
		extend: {
			colors: {
				primary: '#1a365d',
				secondary: '#64748b',
				accent: '#0ea5e9',
			}
		},
	},
	plugins: [],
}