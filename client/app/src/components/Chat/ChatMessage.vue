<template>
  <div
    class="message"
    :class="msg.sender === 'user' ? 'user-message' : 'support-message'"
  >
    <p>{{ msg.text }}</p>
    <small>{{ formatMessageTime(msg.timestamp) }}</small>
  </div>
</template>

<script setup>
import { formatMessageTime } from "@/utils/dateUtils";
</script>
