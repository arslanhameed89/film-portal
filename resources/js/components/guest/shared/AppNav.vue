<template>
    <div>
        <v-app-bar
            color="deep-purple accent-4"
            dense
            dark
        >
            <v-toolbar-title></v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn text v-if="auth" to="/profile">
                Dashboard
            </v-btn>

            <v-btn text v-else to="/login">
                Login
            </v-btn>

        </v-app-bar>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        data: () => ({}),

        computed: mapGetters({
            auth: 'auth/user'
        }),

        watch: {},

        mounted() {

        },

        methods: {}
    }
</script>
