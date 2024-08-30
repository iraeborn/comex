<template>
      <button @click="toggleTheme" class="dropdown-item">
        <i class="fas fa-paint-brush "></i> {{ isDarkTheme ? 'Light Theme' : 'Dark Theme' }}
      </button>
</template>
  
<script>

  export default {
    name: "change-theme",
  
    data() {
      return {
        isDarkTheme: false,
      };
    },
  
    mounted() {
      const savedTheme = localStorage.getItem('theme');
      this.isDarkTheme = savedTheme === 'dark';
      this.updateTheme();
    },
  
    methods: {
      toggleTheme() {
        console.log('toggleTheme: ', this.isDarkTheme);
        this.isDarkTheme = !this.isDarkTheme;
        localStorage.setItem('theme', this.isDarkTheme ? 'dark' : 'light');
        this.updateTheme();
      },
  
      updateTheme() {
        console.log('updateTheme: ',this.isDarkTheme);
        document.documentElement.setAttribute('data-theme', this.isDarkTheme ? 'dark' : 'light');
      },
    },
  };
</script>
  
<style scoped>
  button {
    padding: 0.5em 1em;
    background-color: var(--bg-color);
    color: var(--text-color);
    border: none;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 5px;
  }
  
  button:hover {
    opacity: 0.8;
  }
</style>
  