<template>
    <v-flex xs12 md12>
        <v-row class="full_width" align="center">
            <v-col cols="3">
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
            </v-col>
            <v-col cols="9">
                <v-card
                    class="mx-auto full-width"
                    tile
                >
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title class="display-1">{{film.name}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <v-list-item-subtitle>
                                <span class="font-weight-bold subtitle-1">Price:</span>
                                ${{film.price}}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <v-list-item-subtitle>
                                <span class="font-weight-bold subtitle-1">Release Date:</span>
                                {{film.release_date | humanReadable}}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <v-list-item-subtitle>
                                <span class="font-weight-bold subtitle-1">Rating:</span>
                                <v-rating v-model="film.rating"></v-rating>
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <v-list-item-subtitle>
                                <span class="font-weight-bold subtitle-1">Genre:</span>
                                <v-chip
                                    class="ma-2"
                                    color="primary"
                                    v-for="(genre, index) in film.genres" :key="index"
                                >
                                    {{genre.name}}
                                </v-chip>
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-list-item-content class="py-0">
                            <v-list-item-subtitle>
                                <span class="font-weight-bold subtitle-1">Description:</span>
                                {{film.description}}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                </v-card>
            </v-col>
        </v-row>

        <v-card
            class="mx-auto full_width"
        >
            <v-list two-line>
                <v-list-item-group active-class="pink--text">
                    <template v-for="(item, index) in comments">
                        <v-list-item :key="index" :selectable="true" :ripple="false">
                            <template v-slot:default="{ active, toggle }">
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.user.name"></v-list-item-title>
                                    <v-list-item-subtitle class="text--primary"
                                                          v-text="item.comment"></v-list-item-subtitle>
                                </v-list-item-content>
                            </template>
                        </v-list-item>

                        <v-divider
                            v-if="index + 1 < comments.length"
                            :key="index"
                        ></v-divider>
                    </template>
                </v-list-item-group>
            </v-list>
        </v-card>
        <v-form ref="form" @submit.prevent="submit" lazy-validation v-model="valid" v-if="auth">
            <v-card>
                <v-card-text>

                    <v-textarea
                        label="Enter your comment"
                        v-model="form.content"
                        :error-messages="errors.content"
                        :rules="[rules.required('content')]"
                        :disabled="loading"
                        @input="clearErrors('content')"
                    ></v-textarea>
                </v-card-text>
            </v-card>
            <v-layout mt-12 mx-0>
                <v-spacer></v-spacer>
                <v-btn
                    type="submit"
                    :loading="loading"
                    :disabled="loading"
                    color="primary"
                    class="ml-4"
                >
                    Submit
                </v-btn>
            </v-layout>
        </v-form>
    </v-flex>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import axios from "axios";
    import Form from '~/mixins/form'

    export default {
        mixins: [Form],
        data: () => ({
            film: {},
            comments: [],
            loading: false,
            form: {
                content: null,
                film_id: null
            }
        }),

        computed: mapGetters({
            auth: 'auth/user'
        }),
        filters: {
            humanReadable(date) {
                return moment(date).fromNow();
            }
        },

        methods: {
            ...mapActions({
                getFilmBySlug: 'film/getFilmBySlug',
                getFilmComments: 'film/getFilmComments',
                create_comment: 'film/create_comment',
            }),
            goToDetail(film) {
                this.$router.push({
                    name: 'films-detail',
                    params: {
                        slug: film.slug
                    }
                })
            },
            async submit() {
                if (this.$refs.form.validate()) {
                    this.loading = true;
                    this.form.film_id = this.film.id;
                    this.comments = await this.create_comment(this.form);
                    this.loading = false;
                    this.form.content = null;
                }
            }
        },
        async mounted() {
            this.film = await this.getFilmBySlug({
                slug: this.$router.history.current.params.slug,
                auth: this.auth
            });
            this.comments = await this.getFilmComments({
                film_id: this.film.id,
                auth: this.auth
            });
        }
    }
</script>
