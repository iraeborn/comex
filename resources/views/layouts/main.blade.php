<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="app flex-row align-items-center  pace-done">
    
<template>
  <div class="App">
    <nav class="App__nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </nav>
    <main class="App__main">
      <transition
        name="fade"
        mode="out-in"
      >
        <router-view :current_user="{{$current_user}}"></router-view>
      </transition>
    </main>
    <footer class="App__footer">
      &copy; Digital Rock
    </footer>
  </div>
</template>

    <script src="js/main.js"></script>
</body>
</html>