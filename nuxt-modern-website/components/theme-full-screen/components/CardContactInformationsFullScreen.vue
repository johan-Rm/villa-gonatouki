<template>
<div class="card-contact-informations-full-screen">
    
    <div class="vertical-content">
        <div class="vertical-content-text">
        <h4>Nous rejoindre à partir d'Essaouira</h4>
        <p>Suivre la route d'Agadir, au kilomètre 8 prendre la piste à droite direction la villa.</p>
        </div>
    </div>
</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
    name:'CardContactInformationsFullScreen',
    computed: {
        ...mapState({
            page: state => state.pages.item,
            organization: state => state.organizations.item
        })
    }
}
</script>
<style scoped>
.card-contact-informations-full-screen {
    height: 100%;
}

.vertical-content {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

@media screen and (max-width: 40.0625em){
    .vertical-content p {
        padding: 0 1.5rem;
    }
}
</style>
