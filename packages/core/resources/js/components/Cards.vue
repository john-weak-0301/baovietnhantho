<template>
    <div v-if="filteredCards.length > 0" class="d-cards row mb-3">
        <card-wrapper
            v-for="card in filteredCards"
            :card="card"
            :key="`${card.component}.${card.name}`"
        />
    </div>
</template>

<script>
    export default {
        props: {
            cards: Array,
        },

        computed: {
            filteredCards() {
                return _.filter(this.cards);
            },
        },
    }
</script>
