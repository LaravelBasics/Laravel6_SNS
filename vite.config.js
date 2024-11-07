import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'], // 複数の入力ファイルを指定することができる
            refresh: true, // 開発中にファイルが変更されたときに自動でリフレッシュ
        }),
    ],
});
