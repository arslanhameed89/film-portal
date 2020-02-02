<template>
    <v-row class="mx-auto main_wrapper">
        <v-flex class="panes-container full_height lg12 sm12 xl12 md12 xs12">
            <v-card fluid grid-list-md v-bind:class="cardClass">
                <v-flex class="my-12 mx-auto card_wrapper">
                    <div class="subtitle-1 mb-2 text-uppercase"><strong>Create Film Record</strong></div>
                    <v-card class="pa-6">
                        <v-row class="mx-auto">
                            <v-form ref="form" @submit.prevent="submit" enctype="multipart/form-data" lazy-validation
                                    v-model="valid">
                                <v-flex xl12 lg12 md12 sm12 sx12 class="mr-12">
                                    <v-card-title>
                                        <div class="subtitle-1 mb-2"><strong>Business Information</strong></div>
                                    </v-card-title>
                                    <v-card-text>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum maiores modi
                                            quidem veniam, expedita quis laboriosam, ullam facere adipisci, iusto,
                                            voluptate sapiente corrupti asperiores rem nemo numquam fuga ab at.</p>

                                        <div class="subtitle-1 mb-2 black--text"><strong>Personal Business</strong>
                                        </div>
                                        <v-row>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Film Name</label>
                                                <v-text-field
                                                    v-model="film.name"
                                                    :label="labels.name"
                                                    solo
                                                    class="mt-1 custom_dropdown"
                                                    :error-messages="errors.name"
                                                    :disabled="loading"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Ticket Price</label>
                                                <v-text-field
                                                    v-model="film.price"
                                                    :label="labels.price"
                                                    solo
                                                    class="mt-1 custom_dropdown"
                                                    :error-messages="errors.price"
                                                    :disabled="loading"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-row>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Upload Logo</label>
                                                <p v-for="(error,key) in errors['logo']" class="error--text">
                                                    {{error}}</p>
                                                <image-input v-model="file">
                                                    <div slot="activator">
                                                        <v-avatar size="175px" v-ripple v-if="!file.imageURL"
                                                                  class="mb-3" tile min-height="180" min-width="160"
                                                                  max-height="180" max-width="160">
                                                            <img src="/images/icons/default-logo.png">
                                                        </v-avatar>
                                                        <v-avatar size="175px" v-else class="mb-3" tile min-height="180"
                                                                  min-width="160" max-height="180" max-width="160">
                                                            <v-img
                                                                class="white--text align-end"
                                                                :src="file.imageURL"
                                                                alt="avatar"
                                                            >
                                                            </v-img>
                                                        </v-avatar>
                                                    </div>
                                                </image-input>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Rating</label>
                                                <v-rating
                                                    v-model="film.rating"
                                                    :label="labels.rating"
                                                    :error-messages="errors.rating"
                                                    :disabled="loading"
                                                ></v-rating>
                                            </v-col>
                                        </v-row>

                                        <v-row justify="start">
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Genre</label>
                                                <v-combobox
                                                    v-model="film.genre"
                                                    :items="genreItems"
                                                    chips
                                                    clearable
                                                    multiple
                                                    prepend-icon="filter_list"
                                                    solo
                                                    :label="labels.genre"
                                                    :error-messages="errors.genre"
                                                    :disabled="loading"
                                                    required
                                                >
                                                    <template v-slot:selection="{ attrs, item, select, selected }">
                                                        <v-chip
                                                            v-bind="attrs"
                                                            :input-value="selected"
                                                            close
                                                            @click="select"
                                                            @click:close="removeGenre(item)"
                                                            :label="labels.genre"
                                                            :error-messages="errors.genre"
                                                            :disabled="loading"
                                                        >
                                                            <strong>{{ item }}</strong>&nbsp;
                                                        </v-chip>
                                                    </template>
                                                </v-combobox>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Release Date</label>
                                                <v-menu
                                                    ref="menu1"
                                                    v-model="film.releaseDate"
                                                    :close-on-content-click="false"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px"
                                                >
                                                    <template v-slot:activator="{ on }">
                                                        <v-text-field
                                                            v-model="film.dateFormatted"
                                                            hint="MM/DD/YYYY format"
                                                            persistent-hint
                                                            prepend-icon="event"
                                                            @blur="film.date = parseDate(film.dateFormatted)"
                                                            v-on="on"
                                                            :label="labels.date"
                                                            :error-messages="errors.date"
                                                            :disabled="loading"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="film.date"
                                                        no-title @input="film.releaseDate = false"
                                                    ></v-date-picker>
                                                </v-menu>

                                            </v-col>
                                        </v-row>

                                        <v-row justify="start">
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Description</label>
                                                <v-textarea
                                                    solo
                                                    name="input-7-4"
                                                    label="Film Description ......"
                                                    rows="7"
                                                    class="custom_dropdown"
                                                    v-model="film.description"
                                                    :error-messages="errors.description"
                                                    :disabled="loading"
                                                ></v-textarea>
                                            </v-col>
                                            <v-col cols="12" sm="4">
                                                <label class="font-weight-bold">Country</label>
                                                <v-autocomplete
                                                    :items="countries"
                                                    v-model="film.country_id"
                                                    label="Country"
                                                    solo
                                                    class="custom_dropdown"
                                                    append-icon="keyboard_arrow_down"
                                                    item-text="name"
                                                    item-value="id"
                                                    :error-messages="errors.country_id"
                                                    :disabled="loading"
                                                ></v-autocomplete>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>

                                    <v-card-actions class="text-right float-right">
                                        <v-btn class="caption mr-3 text-capitalize" large>Button</v-btn>
                                        <v-btn
                                            type="submit"
                                            color="primary"
                                            class="caption text-capitalize"
                                            large
                                        >Submit
                                        </v-btn>
                                    </v-card-actions>
                                </v-flex>
                            </v-form>
                        </v-row>
                    </v-card>
                </v-flex>
            </v-card>
        </v-flex>
    </v-row>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import Form from '~/mixins/form'
    import ImageInput from '../../common/ImageInput';

    export default {
        mixins: [Form],
        components: {
            ImageInput: ImageInput
        },
        data: () => ({
            rules: [
                value => !value || value.size < 2000000 || 'Avatar size should be less than 2 MB!',
            ],
            file: {
                imageFile: null,
                imageURL: null
            },
            countries: [
                {id: 1, name: 'USA'},
                {id: 2, name: 'Dubai'},
                {id: 3, name: 'Abu Dhabi'},
                {id: 4, name: 'Saudi Arabia'},
                {id: 5, name: 'India'},
                {id: 6, name: 'China'},
                {id: 7, name: 'Japan'},
                {id: 8, name: 'Pakistan'}
            ],

            film: {
                date: new Date().toISOString().substr(0, 10),
                dateFormatted: null,
                menu1: false,
                rating: 1,
                genre: [],
                description: null,
                releaseDate: false,
                name: null,
                price: null,
                country_id: null,
                logo: null
            },
            genreItems: [],

        }),
        methods: {
            ...mapActions({
                saveFilm: 'film/saveFilm'
            }),
            formatDate(date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
            },
            parseDate(date) {
                if (!date) return null

                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },
            removeGenre(item) {
                this.film.genre.splice(this.film.genre.indexOf(item), 1)
                this.film.genre = [...this.film.genre]
            },
            getLogo() {
                this.file = Object.assign(this.file, {"imageURL": '/images/' + this.film.logo});
            },
            async submit() {
                this.loading = true
                let formData = new FormData();
                formData.append("film", JSON.stringify(this.film));
                formData.append("logo", this.file.imageFile);

                let response = await this.saveFilm(formData);
                if (response.status === 200) {
                    this.clearErrors();
                    this.$toast.success('Film Created successfully.');
                    this.$router.push({name: 'films-user'})
                }
                this.handleErrors(response.details)
                this.loading = false
            }
        },
        computed: {
            paneClass: function () {
                return (this.active_tab == 3) ? 'lg12 sm12 xl12 md12 xs12' : 'lg4 sm6 xl4 md5 xs12';
            },
            cardClass: function () {
                return (this.active_tab == 3) ? 'full_width' : 'full_width';
            },
            computedDateFormatted() {
                return this.formatDate(this.film.date)
            }
        },
        created() {
            this.form = Object.assign(this.film);
            this.form.logo = null;
        },
        watch: {
            'film.date'(val) {
                this.film.dateFormatted = this.formatDate(this.film.date)
            }
        }
    }
</script>

<style scoped>
    .main_wrapper {
        background: #F4F7FD !important;
    }

    >>> .theme--light.v-tabs-items {
        background: #F4F7FD !important;
    }
</style>
