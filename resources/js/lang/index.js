const lang = document.querySelector('html').getAttribute('lang')

const translations = {}

const requireModules = require.context('./translations', false, /\.js$/)

requireModules.keys().forEach(modulePath => {
    const key = modulePath.replace(/(^.\/)|(.js$)/g, '')

    translations[key] = requireModules(modulePath).default
})

const t = text => translations[lang][text] || text

export default t