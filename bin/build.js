import * as esbuild from 'esbuild'
esbuild.build({
    entryPoints: ['./resources/js/mention.js'],
    outfile: './dist/mention.js',
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'browser',
    treeShaking: true,
    target: ['es2020'],
    minify: true,
})
