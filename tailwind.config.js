module.exports = {
    darkMode: 'class',
    content: [
        "./views/**/*.tpl",
        "./modules/views/**/*.tpl"
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/line-clamp')
    ],
}
