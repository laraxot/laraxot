module.exports = {
    plugins: {
        'postcss-import': {
            resolve(id) {
                return require.resolve(id);
            },
        },
        'postcss-nesting': {},
        autoprefixer: {},
    },
}
