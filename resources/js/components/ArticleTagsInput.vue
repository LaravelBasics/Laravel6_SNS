<template>
  <div class="tag-container">
    <div>
      <!-- ヒドゥンフィールドには文字列の配列形式で設定 -->
      <input
        type="hidden"
        name="tags"
        :value="tagsJson"
      >
      <vue3-tags-input
        v-model="inputValue"
        :tags="displayTags"
        placeholder="タグを5個まで入力できます"
        :add-on-key="[13, 32]"
        :autocomplete-items="filteredItems"
        @on-tags-changed="handleChangeTag"
        @input="handleInput"
      />
      <!-- 自動補完リストの表示 -->
      <ul v-if="showAutocompleteList" class="autocomplete-list">
        <li v-for="item in filteredItems" :key="item.text" @click="selectItem(item)">
          {{ item.text }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import Vue3TagsInput from 'vue3-tags-input';

const vueTagsInputVersion = '1.0.12';  // 手動で設定したバージョン番号
// console.log(`vue3-tags-input version: ${vueTagsInputVersion}`);

export default {
  components: {
    Vue3TagsInput,
  },
  props: {
    initialTags: {
      type: Array,
      default: () => [],
    },
    autocompleteItems: {
      type: Array,
      default: () => [],
    },
  },
  setup(props) {
    const tags = ref([]);
    const inputValue = ref('');
    const showAutocompleteList = ref(false);

    // console.log('Initial Props:', props.initialTags);

    // 初期タグの変換と設定
    tags.value = props.initialTags.map(tag => typeof tag === 'string' ? { text: tag } : tag);
    // console.log('初期タグ:', tags.value);

    const displayTags = computed(() => tags.value.map(tag => tag.text));

    

const filteredItems = computed(() => {
  const normalizedInput = inputValue.value.normalize('NFKC').toLowerCase();
  return props.autocompleteItems.filter(item => {
    return item.text.normalize('NFKC').toLowerCase().includes(normalizedInput) &&
           !displayTags.value.includes(item.text);
  });
});


    const tagsJson = computed(() => JSON.stringify(tags.value.map(tag => tag.text)));

    const handleChangeTag = (newTags) => {
      // console.log('新しいタグ:', newTags);
      tags.value = newTags.map(tag => {
        if (typeof tag === 'object' && tag.text) {
          return tag;
        }
        if (typeof tag === 'string') {
          return { text: tag };
        }
        console.error('無効なタグデータ:', tag);
        return { text: '' };
      });
      // console.log('更新後のタグ:', tags.value);
      showAutocompleteList.value = false;
    };

    const handleInput = (event) => {
      // console.log('入力イベント:', event);
      inputValue.value = event.target.value;
      showAutocompleteList.value = inputValue.value.length > 0;
    };

    const selectItem = (item) => {
      if (!tags.value.some(tag => tag.text === item.text)) {
        tags.value.push({ text: item.text });
      }
      inputValue.value = '';
      showAutocompleteList.value = false;
    };

    onMounted(() => {
      // console.log('コンポーネントが描画された後の状態');
      // console.log('初期タグ:', tags.value);
      // console.log('Display Tags:', displayTags.value);
    });

    return {
      tags,
      displayTags,
      filteredItems,
      tagsJson,
      handleChangeTag,
      selectItem,
      handleInput,
      inputValue,
      showAutocompleteList,
    };
  },
};
</script>
<style lang="css" scoped>
  .vue3-tags-input {
    max-width: inherit;
  }
</style>
<style>
.autocomplete-list {
  border: 1px solid #ccc;
  position: absolute;
  background: white;
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 100%;
  z-index: 1000; /* リストが他の要素の上に表示されるように */
}
.autocomplete-list li {
  padding: 8px;
  cursor: pointer;
}
.autocomplete-list li:hover {
  background: #eee;
}
.v3ti-tag {
  background: transparent;
  border: 1px solid #747373;
  color: #747373;
  margin-right: 4px;
  border-radius: 0px;
  font-size: 13px;
  
}
.v3ti-tag::before {
  content: "#";
}
</style>
