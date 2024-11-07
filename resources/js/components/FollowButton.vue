<template>
    <div>
      <button
        class="btn-sm shadow-none border border-primary p-2"
        :class="buttonColor"
        @click="clickFollow"
      >
        <i
          class="mr-1"
          :class="buttonIcon"
        ></i>
        {{ buttonText }}
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  
  const props = defineProps({
    initialIsFollowedBy: {
      type: Boolean,
      default: false,
    },
    authorized: {
      type: Boolean,
      default: false,
    },
    endpoint: {
      type: String,
      required: true,
    },
  })
  
  const isFollowedBy = ref(props.initialIsFollowedBy)
  
  const buttonColor = computed(() => 
    isFollowedBy.value ? 'bg-primary text-white' : 'bg-white'
  )
  
  const buttonIcon = computed(() => 
    isFollowedBy.value ? 'fas fa-user-check' : 'fas fa-user-plus'
  )
  
  const buttonText = computed(() => 
    isFollowedBy.value ? 'フォロー中' : 'フォロー'
  )
  
  const clickFollow = async () => {
    if (!props.authorized) {
      alert('フォロー機能はログイン中のみ使用できます')
      return
    }
  
    if (isFollowedBy.value) {
      await unfollow()
    } else {
      await follow()
    }
  }
  
  const follow = async () => {
    await axios.put(props.endpoint)
    isFollowedBy.value = true
  }
  
  const unfollow = async () => {
    await axios.delete(props.endpoint)
    isFollowedBy.value = false
  }
  </script>
  