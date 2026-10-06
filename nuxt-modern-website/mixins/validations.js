// https://www.codepanion.com/posts/2020-02-08-implement-form-validation-from-scratch-using-vue-js-mixins/

// const EMAIL_REGEX = /\S+@\S+\.\S+/
const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
const PHONE_REGEX = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/

const validationMixin = {
  	methods: {
	    validateField(inputName, value) {
		    return this.validationRules[inputName].rules
		    	.filter(rule => {
		        	const isValid = rule(value)

			        if(isValid !== true) {
			          return isValid
			        }
		    	})
		    .map(rule => rule(value))
		},
		validateForm(form) {
		    const formErrors = {}
		    let formIsValid = true
		    for(let property in form) {
		    	// on valide seulement si la regle de validation existe
		    	// sinon on passe outre...
				console.log(property)
		    	if(this.validationRules.hasOwnProperty(property)) {
		    		const errors = this.validateField(property, form[property])

				    if(errors.length) {
				        formIsValid = false
				    }

				    formErrors[property] = errors
		    	}
		    }

		    formErrors.formIsValid = formIsValid

		    return formErrors
		}
  	},
  	data: () => ({
	    validationRules: {
		    email: {
		      rules: [
		        value => EMAIL_REGEX.test(value) || 'Please enter a valid email address'
		      ]
		   	},
		   	phone: {
		      rules: [
		        value => PHONE_REGEX.test(value) || 'Please enter a valid phone number'
		      ]
		   	},
		   	firstname: {
		      rules: [
		        value => !!value || 'Firstname is required',
		        // value => (value.length <= 12) || 'Nickname must be less than 12 characters'
		      ]
		    },
		    lastname: {
		      rules: [
		        value => !!value || 'Lastname is required',
		        // value => (value.length <= 12) || 'Nickname must be less than 12 characters'
		      ]
		    },
		    message: {
		      rules: [
		        value => !!value || 'Message is required',
		        // value => (value.length <= 12) || 'Nickname must be less than 12 characters'
		      ]
		    },
		    // civility: {
		    //   rules: [
		    //     value => !!value || 'Civility is required',
		    //     // value => (value.length <= 12) || 'Nickname must be less than 12 characters'
		    //   ]
		    // },
		    rooms: {
		    	rules: [
		    		value => (value.length > 0) || 'Rooms is required',
		    	]
		    },
		    datesOfStay: {
		    	rules: [
		    		value => (!!value.start && !!value.end) || 'Dates is required',
		    	]
		    }
		}
	})
}

export default validationMixin
