<template>
  <div id="app">
    <message-list></message-list> <!-- Rejestruj komponent MessageList -->
  </div>
</template>

<script>
import MessageList from './components/MessageList.vue';

export default {
  components: {
    MessageList
  }
}
</script>
