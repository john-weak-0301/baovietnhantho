<template>
    <div>
        <div v-if="shouldShowCards">
            <cards v-if="cards.length > 0" :cards="cards"/>
        </div>
    </div>
</template>

<script>
    export default {
        props: {},

        data: () => ({
            cards: []
        }),

        created() {
            this.cards = window.preloadCards || []
        },

        computed: {
            /**
             * Determine whether we have cards to show on the Dashboard
             */
            shouldShowCards() {
                return this.cards.length > 0
            }
        },
    }
</script>
