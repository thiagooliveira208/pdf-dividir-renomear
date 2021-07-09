<template>
  <div id="app">
    <img alt="Vue logo" width="350" height="70" src="./assets/logo.png">
    <!-- <HelloWorld msg="Welcome to Your Vue.js App"/> -->
    <FormatPdf />
  </div>
</template>

<script>
//import HelloWorld from './components/HelloWorld.vue'
import FormatPdf from  './components/FormatPdf'

export default {
  name: 'App',
  components: {
    //HelloWorld,
    FormatPdf
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 10px;
}
</style>
