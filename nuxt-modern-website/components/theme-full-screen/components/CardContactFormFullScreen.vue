<template>
<div class="card-contact-form-full-screen">
    <div class="contact-style2-content">
        <div class="contact-form-header">
            <!-- <div class="aside_wrapper">
                <transition name="slide-fade-left">
                    <aside v-if="show" class="post-meta">
                        <span>Essaouira, Maroc</span>
                    </aside>
                </transition>
            </div> -->
            <h4 class="title">
                {{ organization.addresses[0].address }}
            </h4>
            <h4 class="city">
                {{ organization.addresses[0].city }}, {{ organization.addresses[0].postcode }} {{ organization.addresses[0].country }}
            </h4>
            <h4>
                {{ organization.phone }}<br /> {{ organization.email }}
            </h4>
        </div>
        <div class="contact-form-content">
        <form class="wpcf7-form" novalidate>
            <div class="row align-center">
                 <div class="small-12 medium-6 large-6 columns">
                    <v-text-field
                        :label="$t($getFreeContentLanguageKey('Votre prénom'))"
                        prepend-inner-icon="mdi-account"
                        v-model="form.firstname"
                    ></v-text-field>
                </div>
                <div class="small-12 medium-6 large-6 columns">
                    <v-text-field
                        :label="$t($getFreeContentLanguageKey('Votre nom'))"
                        prepend-inner-icon="mdi-account"
                        v-model="form.lastname"
                    ></v-text-field>
                </div>
            </div>
            <div class="row align-center">
                <div class="small-12 medium-12 large-12 columns">
                    <v-text-field
                        :label="$t($getFreeContentLanguageKey('Votre e-mail'))"
                        prepend-inner-icon="mdi-email"
                        v-model="form.email"
                    ></v-text-field>
                </div>
            </div>
            <!-- <div class="small-12 medium-12 large-12 columns">
                    <v-text-field
                        :label="$t($getFreeContentLanguageKey('Votre e-mail'))"
                        @blur="focusOut('your-email')"
                        v-model="form.email"
                        prepend-inner-icon="mdi-email"
                        ></v-text-field>
                </div> -->
            <div class="row align-center">
                <div class="small-12 medium-12 large-12 columns">
                    <v-textarea
                        :label="$t($getFreeContentLanguageKey('Votre message'))"
                        no-resize
                        rows="3"
                        prepend-inner-icon="mdi-message"
                        v-model="form.message"
                    ></v-textarea>
                </div>
            </div>

             <div class="btn-form-valid">
                <v-btn
                  @click="submit()"
                  class="site-button btn-slide _button-sm"
                  :loading="loading"
                  :disabled="loading"
                >
                    <span>{{ $t($getFreeContentLanguageKey('Envoyer')) }}</span>
                </v-btn>
            </div>
        </form>
        </div>
    </div>
</div>  
</template>

<script>
import { mapState } from 'vuex'
import validationMixin from '~/mixins/validations'
export default {
    name:'ContactFormFullScreen',
    data () {

        return {
            loading: false,
            form: {
                email: "",
                message: "",
                firstname: "",
                lastname: ""
            }
        }
    },
    mixins: [validationMixin],
    computed: {
        ...mapState({
            page: state => state.pages.item,
            organization: state => state.organizations.item,
            show: state => state.organizations.config.transition.show.slider,
        })
    },
    mounted() {
        this.$store.commit('organizations/setTransitionShowSlide', true)
    },
    methods: {
        submit() {
            this.loading = true
           

            // return false
            this.errors = this.validateForm(this.form)
            if(true === this.errors.formIsValid){
                /**
                * GET OR CREATE PERSON
                **/
                const params = { email: this.form.email }
                var person = {} // créer une requete spécif pour get person by email
                this.$axios.get('/people', { params }).then((response) => {
                    person = response.data['hydra:member'][0]
                    if(1 != response.data['hydra:totalItems']) {
                        person = this.createPerson(this.form)
                    } else {
                        /**
                        * CREATE AND SEND MESSAGE
                        **/
                        this.createAndSendMessage(person, this.form)
                    }
                }).catch(error => {
                    console.log(error)
                    console.log('error get /people into CardContactFormFullScreen')
                })
                
                /**
                * FAIRE NOTIFICATION VUETIFY
                **/
                // alert('formIsValid === true!!')
            } else {
                alert('formIsValid === false!!')
            }
        },
        createPerson(form) {
            const params = {
                email: form.email,
                firstname: form.firstname,
                lastname: form.lastname,
                origin: "contact form"
            }
            var person = {}
            this.$axios.post('/people', params )
                .then((response) => {
                person = response.data
                /**
                * CREATE AND SEND MESSAGE
                **/
                this.createAndSendMessage(person, this.form)
            }).catch((e) => { console.log(e) })

            return person
            
        },
        createAndSendMessage(person, form) {
            console.log('person')
            console.log(person)
           
            const message = {
                subject: "unused subject",
                text: form.message,
                dateSent: this.$dayjs().format('YYYY-MM-DD H:m:s'),
                messageAttachment: null,
                origin: "contact form",
                recipient: null,
                sender: person['@id']
            }
            this.$axios.post('/messages', message).then((response) => {
                const params = {
                    message: form.message,
                    firstname: form.firstname,
                    lastname: form.lastname,
                    from: 'johan.remy@graines-digitales.online',//this.$store.state.organizations.item.email,
                    to: form.email,
                    locale: 'fr', //this.$store.state.i18n.currentLocale
                    person: person['id']
                }
                this.$axios.post(
                    process.env.URL_DMS + '/email/confirmation-contact'
                    , params
                ).then((response) => {
                    this.resetForm()
                    // this.snackbar.status = true
                    /**
                    * FAIRE NOTIFICATION VUETIFY
                    **/
                }).catch((e) => { console.log(e) })
            }).catch((e) => { console.log(e) })
        },
        resetForm() {
           this.form.firstname = ""
           this.form.lastname = ""
           this.form.email = ""
           this.form.message = ""
        }
    }
}
</script>

<style>

.card-contact-form-full-screen {
    width: 100%;
    padding: 1.5rem;
}


.card-contact-form-full-screen aside.post-meta span {
	color: #fff;
}

.card-contact-form-full-screen aside.post-meta span::before
, .card-contact-form-full-screen aside.post-meta span::after {
	
	background: #fff;
}

.card-contact-form-full-screen h4 {
	color: #fff;
}

.card-contact-form-full-screen h4.title {
	/* color: var(--color-secondary); */
}

.card-contact-form-full-screen h4.city {
	color: var(--color-secondary);
}


.card-contact-form-full-screen .theme--light.v-input {
	color: #fff;
}

.card-contact-form-full-screen .theme--light.v-icon {
	color: #fff;
}

.card-contact-form-full-screen .contact-form-header .title {
    text-transform: uppercase;
}

.card-contact-form-full-screen .contact-form-content {
    margin-top: 1.5rem;
}

.card-contact-form-full-screen .btn-form-valid {
    text-align: center;
    margin-top: 1.5rem;
}


@media screen and (max-width: 40.625em){
    .card-contact-form-full-screen {
        width: 100%;
        padding: 1.5rem;
        padding-top: 25vh !important;
        height: 100%;
    }
}
</style>
