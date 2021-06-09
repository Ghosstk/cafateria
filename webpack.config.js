const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
        fallback: {
            // "os": false, //require.resolve("os-browserify/browser"),
            // "http": require.resolve("stream-http"),
            // "https": require.resolve("https-browserify"),
            // "stream": require.resolve("stream-browserify"),
            // "crypto": require.resolve('crypto-browserify'), //if you want to use this module also don't forget npm i crypto-browserify
        }
    },
};
