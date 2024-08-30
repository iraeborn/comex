<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Edit user
        </div>
    </div>

  </div>
  <div class="card-body">

    <p v-if="!user" class="text-center">
      Loading user data
    </p>
  
    <div v-if="user">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="name">Importer Name</label>
            <input type="text" name="name" v-model="user.name" class="form-control" placeholder="Importer Name" :class="error.name != '' && error.name ? 'is-invalid' : ''">
            <div class="invalid-feedback" v-if="error.name">{{ error.name.join('') }}</div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" v-model="user.username" class="form-control" placeholder="Username" :class="error.username != '' && error.username ? 'is-invalid' : ''" disabled>
            <div class="invalid-feedback" v-if="error.username">{{ error.username.join('') }}</div>
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" name="email" id="email" v-model="user.email" class="form-control" placeholder="E-mail" :class="error.email != '' && error.email ? 'is-invalid' : ''" disabled>
            <div class="invalid-feedback" v-if="error.email">{{ error.email.join('') }}</div>
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" v-model="user.password" class="form-control" placeholder="Password" :class="error.password != '' && error.password ? 'is-invalid' : ''">
            <div class="invalid-feedback" v-if="error.password">{{ error.password.join('') }}</div>
          </div>
        </div>

        <div class="col">
          <div class="form-group">
            <label for="password_confirmation">Password confirmation</label>
            <input type="password" name="password_confirmation" id="password_confirmation" v-model="user.password_confirmation" class="form-control" placeholder="Password confirmation" :class="error.password_confirmation != '' && error.password_confirmation ? 'is-invalid' : ''">
            <div class="invalid-feedback" v-if="error.password_confirmation">{{ error.password_confirmation.join('') }}</div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-6">
          <div class="form-group">
            <label for="profile_picture">Profile picture</label>
            <popcorn-upload name="profile_picture" :allowed_pattern="/^image\/(jpe?g|png)$/" @upload="RegisterImage" :file="user.profile_picture"></popcorn-upload>
          </div>
        </div>
      </div>

      <div class="alert alert-danger" v-if="error.country || error.zip || error.address_1 || error.address_2 || error.number || error.state || error.city || error.neighborhood">
        There are errors in address
      </div>
      <div class="alert alert-danger" v-if="ContactErrors(error)">
        There are errors in contact
      </div>

      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="address" role="tabpanel" aria-labelledby="address-tab">

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="name">Country</label>
                <!-- <vue-select2 :class="error.country != '' && error.country ? 'is-invalid' : ''" v-model="user.country" :options="countries"></vue-select2> -->
                <vue-select2 class="vue-select1 form-control" name="select1" :options="countries" :model.sync="user.country" v-model="user.country" :class="error.country != '' && error.country ? 'is-invalid select2-hidden-accessible' : ''"></vue-select2>
                <div class="invalid-feedback" v-if="error.country">{{ error.country.join('') }}</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="zip">Zip</label>
                <input type="text" name="zip" v-model="user.zip" class="form-control" placeholder="Zip" :class="error.zip != '' && error.zip ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.zip">{{ error.zip.join('') }}</div>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <label for="address_1">Address 1</label>
                <input type="text" name="address_1" v-model="user.address_1" class="form-control" placeholder="Address 1" :class="error.address_1 != '' && error.address_1 ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.address_1">{{ error.address_1.join('') }}</div>
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <label for="address_2">Address 2</label>
                <input type="text" name="address_2" v-model="user.address_2" class="form-control" placeholder="Address 1" :class="error.address_2 != '' && error.address_2 ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.address_2">{{ error.address_2.join('') }}</div>
              </div>
            </div>
          
            <div class="col">
              <div class="form-group">
                <label for="number">Number</label>
                <input type="text" name="number" v-model="user.number" class="form-control" placeholder="Number" :class="error.number != '' && error.number ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.number">{{ error.number.join('') }}</div>
              </div>
            </div>

          </div>
          <div class="row">
          
            <div class="col">
              <div class="form-group">
                <label for="state">State</label>
                <input type="text" name="state" v-model="user.state" class="form-control" placeholder="State" :class="error.state != '' && error.state ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.state">{{ error.state.join('') }}</div>
              </div>
            </div>
          
            <div class="col">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" v-model="user.city" class="form-control" placeholder="City" :class="error.city != '' && error.city ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.city">{{ error.city.join('') }}</div>
              </div>
            </div>
          
            <div class="col">
              <div class="form-group">
                <label for="neighborhood">Neighborhood</label>
                <input type="text" name="neighborhood" v-model="user.neighborhood" class="form-control" placeholder="Neighborhood" :class="error.neighborhood != '' && error.neighborhood ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error.neighborhood">{{ error.neighborhood.join('') }}</div>
              </div>
            </div>
          

          </div>

        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
          <div class="row" v-for="(contact, index) in user.contacts">
            <div class="col-3">
              <div class="form-group">
                <label for="contact_type_id">Contact type</label>
                <select name="contact_type_id" v-model="contact.contact_type_id" class="form-control" placeholder="Contact type" :class="error['contacts.'+index+'.contact_type_id'] != '' && error['contacts.'+index+'.contact_type_id'] ? 'is-invalid' : ''">
                  <option value="">Contact Type</option>
                  <option v-for="ct in contact_types" :value="ct.id">{{ ct.name }}</option>
                </select>
                <div class="invalid-feedback" v-if="error['contacts.'+index+'.contact_type_id']">{{ error['contacts.'+index+'.contact_type_id'].join('') }}</div>
              </div>
            </div>

            <div class="col-7">
              <div class="form-group">
                <label for="value">Contact</label>
                <input type="text" name="value" v-model="contact.value" class="form-control" placeholder="Contact" :class="error['contacts.'+index+'.value'] != '' && error['contacts.'+index+'.value'] ? 'is-invalid' : ''">
                <div class="invalid-feedback" v-if="error['contacts.'+index+'.value']">{{ error['contacts.'+index+'.value'].join('') }}</div>
              </div>

            </div>

            <div class="col-2 align-bottom">
              <label for="">&nbsp;</label><br>
              <button type="text" @click="RemoveContact(index)" class="btn btn-danger btn-block"><i class="trash"></i>Remove</button>
            </div>
          </div>
        
          <div class="row">
            <div class="col text-right">
              <input type="button" class="btn btn-success" value="Add new contact" @click="AddNewContact()">
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <div class="card-footer text-right">
    <router-link :to="{ name: 'Dashboard' }" class="btn btn-danger">Cancel</router-link>
    <input type="button" value="Save" @click="Store()" class="btn btn-success">
  </div>

</div>



                </div>

            </div>

<!-- <popcorn-upload></popcorn-upload> -->
    </div>

</template>

<script>
  export default {
    props: ['current_user'],
    data() {
      return {
        contact_types: [
          {id: 1, name: 'E-mail comercial'},
          {id: 2, name: 'E-mail document'},
          {id: 3, name: 'Phone comercial'},
          {id: 4, name: 'Phone document'}
        ],
        user: null,
        error: {},
      }
    },
    mounted: function () {
      this.$data.user = this.$props.current_user;
    },
      computed:{
      countries(){
        return [
          'Afghanistan',
          'Albania',
          'Algeria',
          'Andorra',
          'Angola',
          'Antigua and Barbuda',
          'Argentina',
          'Armenia',
          'Aruba',
          'Australia',
          'Austria',
          'Azerbaijan',
          'Bahamas, The',
          'Bahrain',
          'Bangladesh',
          'Barbados',
          'Belarus',
          'Belgium',
          'Belize',
          'Benin',
          'Bhutan',
          'Bolivia',
          'Bosnia and Herzegovina',
          'Botswana',
          'Brazil',
          'Brunei',
          'Bulgaria',
          'Burkina Faso',
          'Burma',
          'Burundi',
          'Cambodia',
          'Cameroon',
          'Canada',
          'Cabo Verde',
          'Central African Republic',
          'Chad',
          'Chile',
          'China',
          'Colombia',
          'Comoros',
          'Congo, Democratic Republic of the',
          'Congo, Republic of the',
          'Costa Rica',
          'Cote d\'Ivoire',
          'Croatia',
          'Cuba',
          'Curacao',
          'Cyprus',
          'Czechia',
          'Denmark',
          'Djibouti',
          'Dominica',
          'Dominican Republic',
          'Ecuador',
          'Egypt',
          'El Salvador',
          'Equatorial Guinea',
          'Eritrea',
          'Estonia',
          'Eswatini',
          'Ethiopia',
          'Fiji',
          'Finland',
          'France',
          'Gabon',
          'Gambia, The',
          'Georgia',
          'Germany',
          'Ghana',
          'Greece',
          'Grenada',
          'Guatemala',
          'Guinea',
          'Guinea-Bissau',
          'Guyana',
          'Haiti',
          'Holy See',
          'Honduras',
          'Hong Kong',
          'Hungary',
          'Iceland',
          'India',
          'Indonesia',
          'Iran',
          'Iraq',
          'Ireland',
          'Israel',
          'Italy',
          'Jamaica',
          'Japan',
          'Jordan',
          'Kazakhstan',
          'Kenya',
          'Kiribati',
          'Korea, North',
          'Korea, South',
          'Kosovo',
          'Kuwait',
          'Kyrgyzstan',
          'Laos',
          'Latvia',
          'Lebanon',
          'Lesotho',
          'Liberia',
          'Libya',
          'Liechtenstein',
          'Lithuania',
          'Luxembourg',
          'Macau',
          'Macedonia',
          'Madagascar',
          'Malawi',
          'Malaysia',
          'Maldives',
          'Mali',
          'Malta',
          'Marshall Islands',
          'Mauritania',
          'Mauritius',
          'Mexico',
          'Micronesia',
          'Moldova',
          'Monaco',
          'Mongolia',
          'Montenegro',
          'Morocco',
          'Mozambique',
          'Namibia',
          'Nauru',
          'Nepal',
          'Netherlands',
          'New Zealand',
          'Nicaragua',
          'Niger',
          'Nigeria',
          'North Korea',
          'Norway',
          'Oman',
          'Pakistan',
          'Palau',
          'Palestinian Territories',
          'Panama',
          'Papua New Guinea',
          'Paraguay',
          'Peru',
          'Philippines',
          'Poland',
          'Portugal',
          'Qatar',
          'Romania',
          'Russia',
          'Rwanda',
          'Saint Kitts and Nevis',
          'Saint Lucia',
          'Saint Vincent and the Grenadines',
          'Samoa',
          'San Marino',
          'Sao Tome and Principe',
          'Saudi Arabia',
          'Senegal',
          'Serbia',
          'Seychelles',
          'Sierra Leone',
          'Singapore',
          'Sint Maarten',
          'Slovakia',
          'Slovenia',
          'Solomon Islands',
          'Somalia',
          'South Africa',
          'South Korea',
          'South Sudan',
          'Spain',
          'Sri Lanka',
          'Sudan',
          'Suriname',
          'Swaziland',
          'Sweden',
          'Switzerland',
          'Syria',
          'Taiwan',
          'Tajikistan',
          'Tanzania',
          'Thailand',
          'Timor-Leste',
          'Togo',
          'Tonga',
          'Trinidad and Tobago',
          'Tunisia',
          'Turkey',
          'Turkmenistan',
          'Tuvalu',
          'Uganda',
          'Ukraine',
          'United Arab Emirates',
          'United Kingdom',
          'United States',
          'Uruguay',
          'Uzbekistan',
          'Vanuatu',
          'Venezuela',
          'Vietnam',
          'Yemen',
          'Zambia',
          'Zimbabwe',
        ].map(function(x){ return x.toUpperCase(); })
      }
    },
    methods: {
      Store: function () {
        this.$data.error = {}
        this.$http.put('/api/user', this.$data.user).then(function (e) {
          if (!e.body.success) {
            this.$data.error = e.body.errors
            return;
          }

          this.$toaster.success(e.body.success)
          this.$router.push('/user/me')
        })
      },
      AddNewContact: function () {
        if (!this.$data.user.contacts) this.$data.user.contacts = [];
        this.$data.user.contacts.push({})
      },
      RemoveContact: function (index) {
        this.$data.user.contacts.splice(index, 1)
      },
      RegisterImage: function (e) {
        if ( e ) {
          this.$data.user.profile_picture = e.url
          return
        }

        this.$data.user.profile_picture = null
      },
      ContactErrors: function (errors) {
        for (var i in errors) {
          if (i.match(/^contacts\..*\.value/)) {
            return true
          }
        }

        return false
      }
    }
  }
</script>