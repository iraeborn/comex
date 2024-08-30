<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            New client
        </div>
    </div>

  </div>
  <div class="card-body">

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="name">Importer Name</label>
          <input type="text" name="name" v-model="user.name" class="form-control" placeholder="Importer Name">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" v-model="user.username" class="form-control" placeholder="Username" readonly>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="text" name="email" id="email" v-model="user.email" class="form-control" placeholder="E-mail" readonly>
        </div>
      </div>
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
              <select name="" id="" class="form-control"></select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="zip">Zip</label>
              <input type="text" name="zip" v-model="user.zip" class="form-control" placeholder="Zip">
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="address_1">Address 1</label>
              <input type="text" name="address_1" v-model="user.address_1" class="form-control" placeholder="Address 1">
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="address_2">Address 2</label>
              <input type="text" name="address_2" v-model="user.address_2" class="form-control" placeholder="Address 1">
            </div>
          </div>
        
          <div class="col">
            <div class="form-group">
              <label for="number">Number</label>
              <input type="text" name="number" v-model="user.number" class="form-control" placeholder="Number">
            </div>
          </div>

        </div>
        <div class="row">
        
          <div class="col">
            <div class="form-group">
              <label for="state">State</label>
              <input type="text" name="state" v-model="user.state" class="form-control" placeholder="State">
            </div>
          </div>
        
          <div class="col">
            <div class="form-group">
              <label for="city">City</label>
              <input type="text" name="city" v-model="user.city" class="form-control" placeholder="City">
            </div>
          </div>
        
          <div class="col">
            <div class="form-group">
              <label for="neighborhood">Neighborhood</label>
              <input type="text" name="neighborhood" v-model="user.neighborhood" class="form-control" placeholder="Neighborhood">
            </div>
          </div>
        

        </div>

      </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        
        <div class="row" v-for="(contact, index) in user.contacts">
          <div class="col-3">
            <div class="form-group">
              <label for="contact_type_id">Contact type</label>
              <select name="contact_type_id" v-model="contact.contact_type_id" class="form-control" placeholder="Contact type">
                <option value="">Contact Type</option>
                <option value="1">Phone Comercial</option>
                <option value="2">Phone Documentation</option>
                <option value="3">E-mail Documentation</option>
                <option value="4">E-mail Documentation</option>
              </select>
            </div>
          </div>

          <div class="col-7">
            <div class="form-group">
              <label for="value">Contact</label>
              <input type="text" name="value" v-model="contact.value" class="form-control" placeholder="Contact">
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


  <div class="card-footer text-right">
    <router-link :to="{ name: 'Users' }" class="btn btn-danger">Cancel</router-link>
    <input type="button" value="Save" @click="Save()" class="btn btn-success">
  </div>
</div>



                </div>

            </div>

<!-- <popcorn-upload></popcorn-upload> -->
    </div>

</template>

<script>
  export default {
    data() {
      return {
        user: {
          name: null,
          username: null,
          email: null,
          password: null,
          neighborhood: null,
          city: null,
          state: null,
          zip: null,
          number: null,
          address_1: null,
          address_2: null,
          contacts: [{
            contact_type_id: 0,
            value: null
          }]
        }
      }
    },
    methods: {
      Save: function () {},
      AddNewContact: function () {
        this.$data.user.contacts.push({})
      },
      RemoveContact: function (index) {
        this.user.contacts.splice(index, 1)
      }
    },
    mounted: function () {
    }
  }
</script>