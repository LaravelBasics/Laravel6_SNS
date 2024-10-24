import { defineConfig } from 'vite';
import laravel from 'vite-plugin-laravel';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js', // ここは実際の入力ファイルに合わせて変更してください
            output: 'public/js', // 出力先のディレクトリ
        }),
    ],
});