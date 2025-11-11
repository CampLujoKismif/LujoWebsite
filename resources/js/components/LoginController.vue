<template>
  <div class="max-w-md mx-auto">
    <div v-if="isAuthenticated" class="space-y-6">
      <h3 class="text-xl font-bold text-gray-900 dark:text-white">Welcome back, {{ userName }}</h3>
      <p class="text-gray-600 dark:text-gray-400">
        You are currently signed in as <span class="font-semibold">{{ userEmail }}</span>.
      </p>
      <div class="space-y-3">
        <button
          type="button"
          class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
          @click="$emit('continue')"
        >
          Continue to Family Information
        </button>
        <button
          type="button"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-zinc-700"
          @click="$emit('logout')"
        >
          Sign Out
        </button>
      </div>
    </div>

    <template v-else>
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sign In or Create Account</h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Please sign in to continue, or create a new account if you don't have one.
      </p>

      <div
        v-if="error"
        class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
      >
        <p class="text-red-800 dark:text-red-200 text-sm">{{ error }}</p>
      </div>

      <div v-if="!showRegisterForm" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
          <input
            :value="loginForm.email"
            type="email"
            required
            @input="updateLoginField('email', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
          <input
            :value="loginForm.password"
            type="password"
            required
            @input="updateLoginField('password', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div class="flex items-center">
          <input
            :checked="loginForm.remember"
            type="checkbox"
            id="remember"
            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
            @change="updateLoginField('remember', $event.target.checked)"
          >
          <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Keep me signed in</label>
        </div>

        <button
          type="button"
          class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
          :disabled="processing"
          @click="$emit('login')"
        >
          {{ processing ? 'Signing in...' : 'Sign In' }}
        </button>

        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          Don't have an account?
          <button
            type="button"
            class="text-indigo-600 dark:text-indigo-400 hover:underline"
            @click="$emit('update:showRegisterForm', true)"
          >
            Sign up
          </button>
        </p>
      </div>

      <div v-else class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
          <input
            :value="registerForm.name"
            type="text"
            required
            @input="updateRegisterField('name', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
          <input
            :value="registerForm.email"
            type="email"
            required
            @input="updateRegisterField('email', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
          <input
            :value="registerForm.password"
            type="password"
            minlength="8"
            required
            @input="updateRegisterField('password', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
          <input
            :value="registerForm.password_confirmation"
            type="password"
            minlength="8"
            required
            @input="updateRegisterField('password_confirmation', $event.target.value)"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"
          >
        </div>

        <button
          type="button"
          class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
          :disabled="processing"
          @click="$emit('register')"
        >
          {{ processing ? 'Creating account...' : 'Create Account' }}
        </button>

        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          Already have an account?
          <button
            type="button"
            class="text-indigo-600 dark:text-indigo-400 hover:underline"
            @click="$emit('update:showRegisterForm', false)"
          >
            Sign in
          </button>
        </p>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: 'LoginController',
  props: {
    isAuthenticated: {
      type: Boolean,
      required: true,
    },
    userName: {
      type: String,
      default: '',
    },
    userEmail: {
      type: String,
      default: '',
    },
    error: {
      type: String,
      default: '',
    },
    loginForm: {
      type: Object,
      required: true,
    },
    registerForm: {
      type: Object,
      required: true,
    },
    showRegisterForm: {
      type: Boolean,
      required: true,
    },
    processing: {
      type: Boolean,
      default: false,
    },
  },
  emits: [
    'continue',
    'logout',
    'login',
    'register',
    'update:showRegisterForm',
    'update:loginForm',
    'update:registerForm',
  ],
  methods: {
    updateLoginField(field, value) {
      this.$emit('update:loginForm', {
        ...this.loginForm,
        [field]: value,
      })
    },
    updateRegisterField(field, value) {
      this.$emit('update:registerForm', {
        ...this.registerForm,
        [field]: value,
      })
    },
  },
}
</script>

