module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            zIndex: {
                '100': '100',
            }
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
  }