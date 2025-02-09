import * as esbuild from 'esbuild'
esbuild.build({
    entryPoints: ['./resources/js/tributejs.js'],
    outfile: './dist/tributejs.js',
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    treeShaking: true,
    target: ['es2020'],
    minify: true,
})
