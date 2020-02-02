<template>
    <v-container fluid grid-list-xl>
        <v-layout row justify-center wrap>
            <v-flex>
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Sidemenu from '$comp/ui/Sidemenu'

    export default {
        data: () => ({
            items: []
        })
    }
</script>
