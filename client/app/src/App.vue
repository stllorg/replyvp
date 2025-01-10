<template>
  <img alt="Vue logo" src="./assets/logo.png">
  <ContactUs />
  <MessageList />
</template>

<script>
import ContactUs from './components/ContactUs.vue';
import MessageList from './components/MessageList.vue';

export default {
  name: 'App',
  components: {
    ContactUs,
    MessageList
  },
  data() {
    return {
      messages: [
        {
          username: "samuelmendespy",
          text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
        },
      ]
    }
  }
}
</script>

<style>
@import "bootstrap/dist/css/bootstrap.css";
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
