module.exports = {
    darkMode: 'class',
    content: [
        "./App/Views/**/*.{html,tpl}",
        "./App/Modules/Views/**/*.{html,tpl}"
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
