<template>
  <div class="vue-component bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h3 class="text-lg font-semibold text-blue-900 mb-2">Vue 3 Component</h3>
    <p class="text-blue-700 mb-4">This is a Vue 3 component working alongside Livewire!</p>
    
    <div class="space-y-2">
      <button 
        @click="increment"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium"
      >
        Count: {{ count }}
      </button>
      
      <div v-if="count > 5" class="text-green-600 font-medium">
        🎉 Great job! You've clicked {{ count }} times!
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VueExample',
  data() {
    return {
      count: 0
    }
  },
  methods: {
    increment() {
      this.count++
    }
  }
}
</script>
