<template>
    <!-- Vueコンポーネントのテンプレートを定義するためのタグです。この中にHTMLを記述 -->
    <div>
        <!-- いいねボタンを表します。 -->
         <!-- Bootstrapのクラスを使用して、ボタンのスタイルを設定btn m-0 p-1 shadow-none -->
          <!-- "clickLike"ボタンがクリックされたときにclickLikeメソッドが呼ばれます -->
        <button
            type="button"
            class="btn m-0 p-1 shadow-none"
        >
        <!-- Font Awesomeのアイコンを使ってハートマークを表示 -->
         <!-- :classディレクティブを使用して、isLikedByが真の場合にred-textクラスを追加します。
          これにより、いいねの状態によってアイコンの色が変わる -->
            <i class="fas fa-heart mr-1" 
            :class="{'red-text':this.isLikedBy, 'animated heartBeat fast':this.gotToLike}" 
            @click="clickLike"
            />
        </button>
        <!-- いいねの総数を表示するためのバインディングです。countLikesは、コンポーネント内のデータプロパティで、いいねの数を保持 -->
        {{ countLikes }}
    </div>
    <!-- まとめ、いいね機能を持つボタンを表示するVueコンポーネントのテンプレート部分です。
     ボタンをクリックするとclickLikeメソッドが呼ばれ、いいねの状態が切り替わり、いいねの数が表示される仕組み -->
</template>

<!-- Vueコンポーネントのロジックを記述するためのタグです。この中でJavaScriptのコードを書きます -->
<script>
// Axiosをモジュールとしてインポートしています。これにより、コンポーネント内でaxiosを使用してHTTPリクエストを行うことができます
import axios from 'axios';

export default {//Vueコンポーネントのデフォルトエクスポートを定義しています。これにより、このファイルをインポートするときに、Vueコンポーネントが使用可能
    props: {//コンポーネントが親コンポーネントから受け取るプロパティを定義するオプションです。これにより、データの流れを明確にし、コンポーネントの再利用性が向上
        initialIsLikedBy: {//コンポーネントが初期状態で「いいね」されているかどうかを示します
            type: Boolean,//型: Boolean
            default: false,//デフォルト値: false
        },
        initialCountLikes: {//コンポーネントの初期状態での「いいね」数を示します
            type: Number,
            default: 0,
        },
        authorized: {//ユーザーが操作を行う権限があるかどうかを示します
            type: Boolean,
            default: false,
        },
        articleId: {//操作対象のアーティクルのIDを示します。このプロパティは必須
            type: Number,
            required: true,
        },
        endpoint: {//APIエンドポイントのURLを示します。このプロパティも必須
            type: String,
            required: true,
        },
    },
    data() {
        return {
            isLikedBy: this.initialIsLikedBy,//初期値はthis.initialIsLikedByから取得します。このプロパティは、コンポーネントが初期状態で「いいね」されているかどうかを示します
            countLikes: this.initialCountLikes,//初期値はthis.initialCountLikesから取得します。このプロパティは、コンポーネントが初期状態での「いいね」数を示します
            gotToLike: false,
        };
    },
    methods: {//コンポーネント内の「いいね」数を最新の値に更新する役割を果たします。これにより、ユーザーが行ったアクションが即座に反映され、インタラクティブな体験が提供
        clickLike() {//いいねボタンがクリックされたときに呼び出されるメソッド
            console.log('clickLike');//console.log('clickLike');で、メソッドが呼ばれたことをコンソールに出力
            if (!this.authorized) {//ユーザーが未ログインの場合、アラートを表示し、処理を中断
                alert('いいね機能はログイン中のみ使用できます');
                return;
            }
            this.isLikedBy 
            ? this.unlike() 
            : this.like();//ユーザーがログインしている場合、isLikedByの状態に応じてlike()またはunlike()メソッドを呼び出します
        },
        async like() {//いいねを追加するための非同期メソッド
            // console.log(`Sending request to: ${this.endpoint}`);//リクエストを送信するエンドポイントを表示
            // try {
                const response = await axios.put(this.endpoint);//Axiosを使ってPUTリクエストを指定されたエンドポイントに送信
                this.isLikedBy = true;//リクエストが成功した場合、isLikedByをtrueに設定し、サーバーから返されたいいね数をcountLikesに更新
                this.countLikes = response.data.countLikes;//Axiosのリクエストが成功したときに、サーバーから返されるレスポンスオブジェクトのdataプロパティの中に含まれているcountLikesを参照しています。これは、サーバーが最新の「いいね」数を返す場合に、その値を指します
                // console.log('Response:', response);
                this.gotToLike = true
            // } catch (error) {//エラーハンドリングとして、エラーが発生した場合には、エラーメッセージをコンソールに表示
            //     console.error('Error:', error.response ? error.response.data : error);
            // }
        },
        async unlike() {//いいねを削除するための非同期メソッド
            // try {
                const response = await axios.delete(this.endpoint);//Axiosを使ってDELETEリクエストを指定されたエンドポイントに送信
                this.isLikedBy = false;//リクエストが成功した場合、isLikedByをfalseに設定し、サーバーから返されたいいね数をcountLikesに更新
                this.countLikes = response.data.countLikes;//this.countLikes:コンポーネント内で定義されているデータプロパティで、現在の「いいね」数を保持
                // console.log('Response:', response);
                this.gotToLike = false
            // } catch (error) {
            //     console.error('Error:', error.response ? error.response.data : error);
            // }
        },
    },
};
</script>
