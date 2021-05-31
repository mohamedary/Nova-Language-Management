<template>
    <div>
        <heading class="mb-6">Language Management</heading>
        <card>
            <div class="flex py-3 pl-3 relative rounded-md" v-for="(language, index) in languages" :key="languages">
                <div class="flex border-b border-40 mr-3">
                    <label class="mr-2">
                        Locale
                        <input type="text" v-model="index" readonly class="w-full form-control form-input form-input-bordered">
                    </label>
                    <label class="mr-2">
                        Name
                        <input type="text" v-model="language.name" class="w-full form-control form-input form-input-bordered">
                    </label>
                    <label class="mr-2">
                        Script
                        <input type="text" v-model="language.script" class="w-full form-control form-input form-input-bordered">
                    </label>
                    <label class="mr-2">
                        Native
                        <input type="text" v-model="language.native" class="w-full form-control form-input form-input-bordered">
                    </label>
                    <label class="mr-2">
                        Regional
                        <input type="text" v-model="language.regional" class="w-full form-control form-input form-input-bordered">
                    </label>

                    <button class="flex justify-center items-center cursor-pointer mt-4" v-on:click="deleteLanguage(index)"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" class="fill-current"><path d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 012 2v2h5a1 1 0 010 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 110-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 011 1v6a1 1 0 01-2 0v-6a1 1 0 011-1zm4 0a1 1 0 011 1v6a1 1 0 01-2 0v-6a1 1 0 011-1z"></path></svg></button>
                </div>
            </div>
        </card>

        <div class="flex items-center mt-2">
            <label class="mr-2">
                Locale
                <input type="text" v-model="locale" class="w-full form-control form-input form-input-bordered">
            </label>
            <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative mt-4" v-on:click="addLanguage(locale)">Add Language</button>
        </div>

        <div class="flex items-center mt-2">
            <button type="submit" class="btn btn-default btn-primary inline-flex items-center relative ml-auto" v-on:click="submit">Submit</button>
        </div>
    </div>
</template>

<script>
export default {
    metaInfo() {
        return {
          title: 'Language Management',
        }
    },
    data() {
        return {
          languages: [],
          locale: ''
      }
    },
    methods: {
      async getAvailableLanguages() {
          await Nova.request()
              .get('/nova-vendor/language-management/languages')
              .then(response => {
                  this.languages = response.data;
              })
              .catch(error => {
                  console.log(error);
              });
      },
      async submit() {
          await Nova.request()
            .post('/nova-vendor/language-management/languages', this.languages)
            .then(response => {
                this.$toasted.show('Languages have been saved.', { type: 'success' });
                console.log(response.data);
            })
           .catch(error => {
               console.log(error);
           })
      },
      deleteLanguage(language) {
          this.$delete(this.languages, language);
      },
      addLanguage(locale) {
          let language = {
              name: '',
              script: '',
              native: '',
              regional: ''
          }

          if(locale === '')
              return this.$toasted.show('Please enter a locale', { type: 'error' });
          if(locale.length > 6)
              return this.$toasted.show('Locale should be smaller than 6 characters', { type: 'error' })

          this.$set(this.languages, locale, language);
      }
    },
    created() {
        this.getAvailableLanguages();
    },
}
</script>

<style>
/* Scoped Styles */
</style>
