<template>
    <v-flex xs12 md12>
        <v-card outlined tile >
            <v-row class="full_width" align="center">
                <v-col class="d-flex" cols="4" v-for="(film, index) in films" :key="index">
                    <v-card
                        :loading="loading"
                        class="mx-auto my-2"
                        max-width="320"
                        @click="goToDetail(film)"
                    >
                        <div class="img-container">
                            <v-img
                                :src="'/images/'+film.photo"
                                alt="John"
                                height="250"
                                class="white--text align-end"
                                v-if="film.photo != ''"
                            ></v-img>
                            <v-img
                                src="/images/icons/default-logo.png"
                                alt="John"
                                height="250"
                                v-else
                            ></v-img>
                        </div>
                        <v-card-title>{{film.name}}</v-card-title>

                        <v-card-subtitle class="pb-0">$ {{film.price}}</v-card-subtitle>

                        <v-card-text class="text--primary">
                            <div>{{film.description}}</div>
                        </v-card-text>

                    </v-card>
                </v-col>
            </v-row>
        </v-card>
    </v-flex>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
export default {
  data: () => ({
      films: [],
      loading: false,
  }),

    computed: mapGetters({
        auth: 'auth/user'
    }),

  methods: {
      ...mapActions({
          getAllFilms : 'film/fetchAllFilms',
      }),
      goToDetail(film){
          if(_.isNil(this.auth)) {
              this.$router.push({
                  name: 'films-detail',
                  params: {
                      slug: film.slug
                  }
              })
              return;
          }
          this.$router.push({
              name: 'films-detail-user',
              params: {
                  slug: film.slug
              }
          })
      }
  },
    async mounted() {
      if(_.isNil(this.auth)){
          this.films = await this.getAllFilms();
      }else {
          this.films = await this.getAllFilms(this.auth);
      }
    }
}
</script>
