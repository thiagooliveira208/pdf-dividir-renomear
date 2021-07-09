<template>
  <div id="app">
    <div class="container p-2">

      <header>
        <h1>Dividir e Renomear PDF</h1>
      </header>

      <!-- Colors -->
      <section class="mb-4">
        <b-form-file :key="fileInputKey" name="file" ref="files" accept=".pdf" placeholder="Selecione um arquivo"></b-form-file>
      </section>

      <!-- Buttons -->
      <section class="mb-4">
        <h4 class="mb-3">Parâmetros</h4>
        <div class="row">
          <label for="paginas" id="textoLabel" class="col-sm-3">Insira o intervalo de páginas: </label>
          <input v-model="nPages" id="paginas" type="number" class="form-control col-sm-1">

          <label for="palavra1" id="textoLabel" class="col-sm-3">Palavra-chave para início do nome: </label>
          <input v-model="key1" id="palavra1" type="text" class="form-control col-sm-1">

          <label for="palavra2" id="textoLabel" class="col-sm-3">Palavra-chave para fim do nome: </label>
          <input v-model="key2" id="palavra2" type="text" class="form-control col-sm-1">
        </div>

        <div class="row col-sm-12" id="divBtn">

          <div class="col-sm-4" id="btnProcess">
            <b-button pill variant="primary" @click="sendFile" block>
              <b-icon icon="gear-fill" aria-hidden="true"></b-icon>
              Processar
            </b-button>
          </div>
          
          <div class="col-sm-4" id="btnDownload">
            <b-button pill :disabled="!download" v-bind:href="'https://shrouded-cliffs-30290.herokuapp.com/api/download-pdf/' + fileName" block variant="info">
              <b-icon icon="download" aria-hidden="true"></b-icon>
              Download
            </b-button>
          </div>

          <loading :active.sync="isLoading" 
          :can-cancel="false"
          :color="color"
          :width="width"
          :height="height"
          :is-full-page="fullPage"></loading>
          
        </div>

      </section>

    </div>
  </div>
</template>

<script>

// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  name: 'app',
  components: {
    Loading
  },
  watch: {
      title: {
          immediate: true,
          handler() {
              document.title = 'PDF Reajuste';
          }
      }
  },
  data () {
    return {
      download: false,
      fileName: '',
      fileInputKey: 0,
      nPages: '',
      key1: '',
      key2: '',
      isLoading: false,
      fullPage: true,
      color: '#00008B',
      width: 110,
      height: 200,
    }
  },
  methods: {

    async sendFile() {
      
      let dataForm = new FormData();

      if(this.$refs.files.files.length == 0){
        alert('Selecione um arquivo');
        return false;
      }

      this.isLoading = true;

      for (let file of this.$refs.files.files) {
        dataForm.append(`file`, file);
        this.fileName = file.name;
      }

      dataForm.append('nPages', this.nPages);
      dataForm.append('key1', this.key1);
      dataForm.append('key2', this.key2);

      let res = await fetch(`https://shrouded-cliffs-30290.herokuapp.com/api/uploading-file-api`, {
        method: 'POST',
        body: dataForm,
        mode: 'no-cors'
      }).catch(function(error) {
        alert('There has been a problem with your fetch operation: ' + error.message);
      });

      let data = await res;
      
      //HABILITAR DOWNLOAD
      this.download = true;
      this.isLoading = false;

      //LIMPAR INPUT FILE
      this.fileInputKey++;

      console.log(data);
    },
  }
}
</script>

<style>
#textoLabel {
  font-size: 16px;
}
#divProcess {
  margin-top: 5%;
}

#divBtn {
  display: flex;
  align-items: center;
  justify-content: center;
  padding-top: 40px;
}
</style>