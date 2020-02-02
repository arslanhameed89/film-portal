<template>
    <div class="fill-height">
        <app-nav></app-nav>

        <v-content>
            <v-container fluid>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>

        <app-footer></app-footer>
    </div>
</template>

<script>
    import AppNav from './shared/AppNav'
    import AppFooter from './shared/AppFooter'

    export default {
        data: () => ({
            mini: false
        }),

        components: {
            AppNav,
            AppFooter
        },

        methods: {}
    }
</script>
