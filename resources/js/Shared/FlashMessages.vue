<template>
  <div class="fixed flex bottom-0 right-0 bg-white rounded shadow m-4">
    <div v-if="$page.props.flash.success && show" class=" flex items-center justify-between bg-green-500 rounded max-w-3xl">
      <div class="flex items-center">
        <div class="p-4 text-white text-sm font-medium">{{ $page.props.flash.success }}</div>
      </div>
      <button type="button" class="group p-2" @click="show = false">
        <svg class="block w-2 h-2 fill-green-800 group-hover:fill-white" xmlns="http://www.w3.org/2000/svg" width="235.908" height="235.908" viewBox="278.046 126.846 235.908 235.908"><path d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z" /></svg>
      </button>
    </div>
    <div v-if="$page.props.flash.error && show" class="flex items-center justify-between bg-red-400 rounded max-w-3xl">
      <div class="flex items-center">
        <div class="py-4 text-white text-sm font-medium">{{ $page.props.flash.error }}</div>
      </div>
      <button type="button" class="group p-2" @click="show = false">
        <svg class="block w-2 h-2 fill-red-800 group-hover:fill-white" xmlns="http://www.w3.org/2000/svg" width="235.908" height="235.908" viewBox="278.046 126.846 235.908 235.908"><path d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z" /></svg>
      </button>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        show: true,
        timeout: null,
      }
    },
    watch: {
      '$page.props.flash': {
        handler() {
          this.show = true;
          if(this.timeout) {
            clearTimeout(this.timeout);
          }
          this.timeout = setTimeout(() => this.show = false, 2500);
        },
        deep: true,
        immediate: true,
      },
    }
  }
</script>
