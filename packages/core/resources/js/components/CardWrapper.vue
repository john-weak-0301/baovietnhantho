<template>
    <div class="col" :class="widthClass" :key="`${card.component}.${card.name}`">
        <component
            :class="cardSizeClass"
            :is="card.component"
            :card="card"
        />
    </div>
</template>

<script>
    function calculateCardWidth(card) {
        return Number.isInteger(card.width) ? `col-${card.width}` : 'col-4'
    }

    export default {
        props: {
            card: {
                type: Object,
                required: true,
            },

            size: {
                type: String,
                default: '',
            }
        },

        computed: {
            widthClass() {
                return this.size === 'large' ? 'col-12' : calculateCardWidth(this.card)
            },

            cardSizeClass() {
                return this.size !== 'large' ? 'd-card-panel' : ''
            },
        },
    }
</script>
