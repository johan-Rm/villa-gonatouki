<template>
<div class="booking-form-container">
  <form @submit.prevent="handleSubmit" class="wpcf7-form" novalidate>
    <p>
      <div class="large-6 columns">
        <span class="wpcf7-form-control wpcf7-checkbox wpcf7-validates-as-required">
          <span
            v-for="(value, index) in rooms"
            :key="index"
            class="wpcf7-form-control-wrap your-rooms"
          >
            <label>
              <input
                v-model="form.rooms[value.slug]"
                :value="value.slug"
                type="checkbox"
              >
              <span class="wpcf7-list-item-label">{{ $t($dataKey($i18n.locale, 'name', 'Room', value.slug)) }}</span>
            </label>
            <label>
              <select
                v-model="form.numberOfRooms[value.slug]"
                class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required"
                aria-required="true"
                aria-invalid="false"
              >
                <option
                  v-for="index in 3"
                  :key="index"
                  :value="index"
                >
                {{ index }}
                </option>
              </select>
            </label>
          </span>
        </span>
      </div>
      <div class="large-6 columns">
        <span class="wpcf7-form-control wpcf7-radio wpcf7-validates-as-required">
          <span class="wpcf7-form-control-wrap your-civility">
            <label>
              <input type="radio" name="your-civility" value="Female" v-model="form.civility">
              <span class="wpcf7-list-item-label">{{ $t($getFreeContentLanguageKey('Femme')) }}</span>
            </label>
          </span>
          <span class="wpcf7-form-control-wrap your-civility">
            <label>
              <input type="radio" name="your-civility" value="Male" v-model="form.civility">
              <span class="wpcf7-list-item-label">{{$t($getFreeContentLanguageKey('Homme')) }}</span>
            </label>
          </span>
        </span>
      </div>
    </p>
    <p>
      <div class="large-6 columns">
        <span class="wpcf7-form-control-wrap your-firstname">
          <input type="text" name="your-firstname" value="YOUR FIRSTNAME" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required full" aria-required="true" aria-invalid="false" v-model="form.firstname"/>
        </span>
      </div>
      <div class="large-6 columns">
        <span class="wpcf7-form-control-wrap your-lastname">
          <input type="text" name="your-lastname" value="YOUR LASTNAME" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required full" aria-required="true" aria-invalid="false" v-model="form.lastname"/>
        </span>
      </div>
      <div class="spacer"></div>
    </p>
    <p>
      <div class="large-6 columns">
        <span class="wpcf7-form-control-wrap your-email">
          <input type="email" name="your-email" value="YOUR EMAIL" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email full" aria-required="true" aria-invalid="false" v-model="form.email"/>
        </span>
      </div>
      <div class="large-6 columns">
        <span class="wpcf7-form-control-wrap your-phone">
          <input type="email" name="your-phone" value="YOUR PHONE" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email full" aria-required="true" aria-invalid="false" v-model="form.phone"/>
        </span>
      </div>
      <div class="spacer"></div>
    </p>
    <p>
      <div class="large-12 columns">
        <span class="wpcf7-form-control-wrap your-message">
          <textarea name="your-message" cols="30" rows="3" class="wpcf7-form-control wpcf7-textarea full" aria-invalid="false" v-model="form.message">YOUR MESSAGE</textarea>
        </span>
      </div>
      <div class="spacer"></div>
    </p>
    <p>
      <input type="submit" value="Send" class="wpcf7-form-control wpcf7-submit btn" />
    </p>
  </form>
</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name:'BookingFormContactFullScreen',
  data () {

    return {
      loading: false,
      errors: {
        labels: {
          error: {
              title: this.$i18n.t('Une erreur est survenue'),
              text: ''
          },
          success: {
              title: this.$i18n.t('Votre message a bien été envoyé'),
              text: this.$i18n.t('Nous vous répondrons dans les meilleurs délais')
          },
          firstname: this.$i18n.t('veuillez saisir un prénom'),
          lastname: this.$i18n.t('veuillez saisir un nom'),
          email: this.$i18n.t('veuillez saisir un email'),
          email_valid: this.$i18n.t('veuillez saisir un email valide'),
          phone: this.$i18n.t('veuillez saisir un numéro de téléphone'),
          phone_valid: this.$i18n.t('veuillez saisir un numéro de téléphone valide'),
          message: this.$i18n.t('veuillez saisir un message')
        },
        fields: {
          firstname: '',
          lastname: '',
          email: '',
          phone: '',
          message: ''
        }
      },
      form: {
        email: "",
        phone: "",
        firstname: "",
        lastname: "",
        message: "",
        rooms: [],
        numberOfRooms: []
      }
    }
  },
  computed: {
    ...mapState({
        rooms: state => state.hotels.rooms,
        datesOfStay: state => state.hotels.booking.datesOfStay
    })
  },
  methods: {
    handleSubmit() {
      this.loading = true
      // console.log('handleSubmit()')
      // console.log(this.form)
      // console.log(this.datesOfStay)
      // console.log(Object.keys(this.datesOfStay).length)
      if(Object.keys(this.datesOfStay).length < 1) {
        alert('veuillez selectionner vos dates')

        return false
      }

      if(this.checkForm()){
        const params = { email: this.form.email }
        this.$axios.get('/people').then((response) => {
          if(0 == response.data['hydra:totalItems']) {
              this.savePerson(response.data)
          } else {
              this.saveMessage(response.data['hydra:member'])
          }
        }).catch(error => {
          console.log(error)
          console.log('error store ContactForm.vue')
        })
      } else {
        alert('form non valid')
      }
    },
    savePerson() {
        const params = {
            email: this.form.email,
            phone: this.form.phone,
            firstname: this.form.firstname,
            lastname: this.form.lastname,
            text: this.form.message,
            origin: "classic contact form"
        }
        this.$axios.post('/people', params )
          .then((response) => {
            this.saveMessage(response.data)
        }).catch((e) => { console.log(e) })
    },
    saveMessage(person) {
      const message = {
            subject: "unused subject",
            text: this.form.message,
            dateSent: this.$dayjs().format('YYYY-MM-DD H:m:s'),
            messageAttachment: null,
            origin: "classic contact form",
            recipient: null,
            sender: person[0]['@id']
      }
       this.$axios.post('/messages', message)
          .then((response) => {

            this.loading = false

            const params = {
                message: this.form.message,
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                from: this.$store.state.organization.item.email,
                to: this.form.email,
                locale: this.$store.state.i18n.currentLocale
                // booking: {}
            }
            this.$axios.post(process.env.DMS_URL + '/email/confirmation-contact', params)
              .then((response) => {
                allert('success')
                // this.showNotification('success', this.errors.labels.success.title, [this.errors.labels.success.text])
            }).catch((e) => { console.log(e) })

            this.form.firstname = ""
            this.form.lastname = ""
            this.form.email = ""
            this.form.phone = ""
            this.form.message = ""

        }).catch((e) => { console.log(e) })
    },
    checkForm() {

        let messages = []
        this.errors.fields.firstname = ''
        this.errors.fields.lastname = ''
        this.errors.fields.email = ''
        this.errors.fields.phone = ''
        this.errors.fields.message = ''

        if (!this.form.firstname) {
            this.errors.fields.firstname = 'red'
            messages.push(this.errors.labels.firstname);
        }
        if (!this.form.lastname) {
            this.errors.fields.lastname = 'red'
            messages.push(this.errors.labels.lastname);
        }
       if (!this.form.email) {
            this.errors.fields.email = 'red'
            messages.push(this.errors.labels.email);
        } else if (!this.validEmail(this.form.email)) {
            this.errors.fields.email = 'red'
            messages.push(this.errors.labels.email_valid);
        }

        // if (!this.form.phone) {
        //     this.errors.fields.phone = 'red'
        //     messages.push(this.errors.labels.phone);
        // } else if (!this.validPhone(this.form.phone)) {
        //     this.errors.fields.phone = 'red'
        //     messages.push(this.errors.labels.phone_valid);
        // }

        if (!this.form.message) {
            this.errors.fields.message = 'red'
            messages.push(this.errors.labels.message);
        }

        if(!messages.length) {
            return true
        }
        this.loading = false

        // this.showNotification('error', this.errors.labels.error.title, messages)

        return false
    },
    validEmail(email) {
        email = email.trim()
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    },
    validPhone(phone) {
        phone = phone.trim()
        var re = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

        return re.test(phone);
    }
  }
}
</script>
<style scoped>

</style>
