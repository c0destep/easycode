module.exports = {
    darkMode: 'class',
    content: [
        "./app/Views/**/*.{html,tpl,php}",
        "./app/Modules/Views/**/*.{html,tpl,php}"
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
