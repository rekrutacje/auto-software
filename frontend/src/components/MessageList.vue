<template>
  <div>
    <h1>Lista wiadomości</h1>

    <!-- Sortowanie -->
    <div>
      <label for="sortBy">Sortuj po: </label>
      <select v-model="sortBy">
        <option value="id">UUID</option>
        <option value="timestamp">Timestamp</option>
      </select>
      <select v-model="order">
        <option value="ASC">Rosnąco</option>
        <option value="DESC">Malejąco</option>
      </select>
      <button @click="fetchMessages">Sortuj</button>
    </div>

    <!-- Lista wiadomości -->
    <ul>
      <li v-for="message in messages" :key="message.id">
        <!-- {{ message.uuid }} - {{ message.message }} - {{ message.timestamp }}-->
        {{ message.uuid }} - {{ message.timestamp }}
        <button @click="openModal(message)">Pokaż wiadomość</button>
      </li>
    </ul>

    <!-- Modal -->
    <div v-if="showModal" class="modal">
      <div class="modal-content">
        <span @click="showModal = false" class="close">&times;</span>
        <p>{{ modalMessage }}</p>
      </div>
    </div>
  </div>

  <!-- Prosty formularz dodawania wiadomości -->
  <div>
    <h2>Dodaj nową wiadomość</h2>
    <form @submit.prevent="addMessage">
      <label for="newMessage">Treść wiadomości:</label>
      <input v-model="newMessage" id="newMessage" required/>
      <button type="submit">Wyślij</button>
    </form>
  </div>

</template>

<script setup>
import {ref} from 'vue';
import axios from 'axios';

const messages = ref([]);
const sortBy = ref('id');
const order = ref('ASC');
const showModal = ref(false);
const modalMessage = ref('');

const fetchMessages = async () => {
  console.log('Wysyłanie żądania do API...');
  try {
    const {data} = await axios.get(`/api/messages?sortBy=${sortBy.value}&order=${order.value}`);
    messages.value = data;
    console.log('Odpowiedź z API:', data);
  } catch (error) {
    console.error('Błąd pobierania danych z API:', error);
  }
};

const openModal = (message) => {
  modalMessage.value = message.message;
  showModal.value = true;
};

const newMessage = ref('');  // Dla nowej wiadomości

const addMessage = async () => {
  if (!newMessage.value) return;

  try {
    const payload = {
      message: newMessage.value
    };
    const config = {
      headers: {
        'Content-Type': 'application/json',
      },
    };

    // Zapisuję odpowiedź z serwera
    const {data} = await axios.post('/api/message', JSON.stringify(payload), config);

    // Upewniam się, że odpowiedź zawiera wszystkie potrzebne pola
    if (data.id && typeof data.timestamp !== 'undefined') {
      // Dodaje nową wiadomość
      messages.value.push(data);
    } else {
      console.warn("Niekompletna odpowiedź z serwera");
    }

    // Wyczyść pole formularza
    newMessage.value = '';
  } catch (error) {
    console.error('Błąd przy dodawaniu wiadomości:', error);
  }
};

// Początkowe ładowanie wiadomości
fetchMessages();
</script>
