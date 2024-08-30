<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="d-flex">
              <div class="flex-grow-1">Users</div>
              <div class="d-flex">
                <div class="pl-2">
                  <div class="input-group" style="max-width: 150px">
                    <input type="text" placeholder="Filter" class="form-control form-control-sm" v-model="filter" id="filter"/>
                    <div class="input-group-append">
                      <button type="button" class="btn btn-danger btn-sm" @click="filter = ''">
                        X
                      </button>
                    </div>
                  </div>
                </div>
                <div class="pl-2 ">
                  <router-link :to="{ name: 'New user' }" class="btn btn-success btn-sm">
                    Add new user
                  </router-link>
                </div>
                <div class="pl-2">
                  <a href="#" @click="Download" class="btn btn-sm btn-secondary">Save PDF</a>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p class="text-center" v-if="!users_filtered && loading">
              Loading users...
            </p>
            <p class="text-center" v-if="!users_filtered && !loading">
              No users founded
            </p>
            <div
              v-if="users_filtered.length == 0 && !loading"
              class="text-center"
            >
              No users founded
            </div>

            <div class="card-columns">
              <div class="card" v-for="(user, index) in users_filtered">
                <div class="card-body p-2">
                  <div class="d-flex">

                    <div class="user-name pointer" style="font-weight: bold;" v-on:click="copyText(user.name)">
                      {{ user.name }}
                    </div>

                    <div class="ml-auto">
                      <div class="dropdown">
                        <button class="btn btn-secondary btn-lt dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu">
  
                          <router-link :to="{ name: 'Edit user', params: { id: user.id } }" class="dropdown-item">
                            Edit
                          </router-link>
  
                          <button type="button" class="dropdown-item" data-toggle="modal" :href="'#modal-' + index">
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>

                  </div>

                  <hr class="separator">
                  <div class="text-muted text-truncate m-0" style="font-size: smaller;">Primary e-mail</div>
                  <p class="font-weight-normal mb-0 pointer" v-on:click="copyText(user.email)">{{ user.email }}</p>

                  <div class="d-flex flex-column" v-if="user.contact">
                    <div class="d-flex flex-column" v-for="(contact, index) in user.contact" v-if="contact.value !== user.email">
                      <div class="text-muted text-truncate m-0" style="font-size: smaller;">{{ contact_types.filter(e => e.id == contact.contact_type_id)[0].name }}</div>
                      <div class="font-weight-normal pointer" v-on:click="copyText(contact.value)">{{ contact.value }}</div>
                    </div>
                  </div>
                  <hr class="separator">

                  <span class="font-italic d-flex">{{ user.country }}</span>
                  <span class="font-weight-normal" >{{ user.role }}</span>
                  <p class="text-muted text-truncate m-0" style="font-size: smaller;">{{ user.group.name }}</p>
                </div>

                <div class="modal fade modal-danger text-left" :id="'modal-' + index">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Are you sure?</h4>
                          <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true"
                          >
                            &times;
                          </button>
                        </div>
                        <div class="modal-body">
                          <p>
                            Deleting the user {{ user.name }} will lose access
                            to the panel and will no longer be able to verify
                            the order data.
                          </p>
                          <p>
                            This action is irreversible, are you sure you want
                            to continue?
                          </p>
                        </div>
                        <div class="modal-footer">
                          <button
                            type="button"
                            class="btn btn-default"
                            data-dismiss="modal"
                          >
                            Cancel
                          </button>
                          <button
                            type="button"
                            class="btn btn-danger"
                            @click="Exclude(user.id, index)"
                          >
                            Confirm exclusion
                          </button>
                        </div>
                      </div>
                    </div>
                </div>

              </div>
            </div>

            <div class="modal fade" id="modal-contact">
              <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header m-2 p-0">
                    <div class="d-flex flex-column" style="width: calc(100% - 20px);">
                      <div class="text-muted text-truncate pt-1">User Contacts</div>
                    <div class="navbar-brand" style="text-wrap: wrap;font-weight: bold;">{{ user_name }}</div>
                    </div>
                    <button
                      type="button"
                      class="close p-1 m-0"
                      data-dismiss="modal"
                      aria-hidden="true"
                    >
                      &times;
                    </button>
                  </div>
                  <div class="modal-body">
                    <div v-for="(contact, index) in contact_info">
                      <div class="row justify-content-center">
                        <div class="col-1">
                          <i :class="contact_types.filter(e => e.id == contact.contact_type_id)[0].icon"></i>
                        </div>
                        <div class="col-9 text-left">
                          {{ contact.value }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
  .pointer {
    cursor: pointer;
  }
  .card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  .user-name {
    max-width: 95%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    text-align: start;
  }
  .card-columns {
    display: inline-block;
    column-count: 1 !important;
  }

  @media screen and (min-width: 600px) {
    .card-columns {
      column-count: 2 !important;
    }
  }

  @media screen and (min-width: 992px) {
    .card-columns {
      column-count: 3 !important;
    }
  }

  @media screen and (min-width: 1200px) {
    .card-columns {
      column-count: 4 !important;
    }
  }

  @media screen and (min-width: 1600px) {
    .card-columns {
      column-count: 6 !important;
    }
  }


  .btn-group-sm>.btn, .btn-lt {
    padding: 0.25rem;
    font-size: .8rem;
    line-height: 0.5;
    border-radius: 0.2rem;
  }

  /* .separator {
    margin-top: 0;
    margin-bottom: 0;
    width: 100%;
    color: #dddddd;
    border: none;
    height: 1px;
    background-color: #dddddd;
  } */
</style>

<script>
import jsPDF from 'jspdf';

export default {
  data() {
    return {
      users: [],
      users_filtered: [],
      contact_info: [],
      user_name: null,
      loading: true,
      filter: null,
      contact_types: [
        { id: 1, name: "E-mail comercial", icon: "fas fa-at" },
        { id: 2, name: "E-mail document", icon: "fas fa-at" },
        { id: 3, name: "Phone comercial", icon: "fas fa-phone" },
        { id: 4, name: "Phone document", icon: "fas fa-phone" },
      ]
    };
  },
  watch: {
    filter: function (newValue, oldValue) {
      if (!newValue) {
        this.$data.users_filtered = this.$data.users;
        return;
      }

      this.$data.users_filtered = this.$data.users.filter((user) => {
        let filterPattern = new RegExp(newValue.toLowerCase());
        if (!!user.name.toLowerCase().match(filterPattern)) return true;
        if (!!user.country.toLowerCase().match(filterPattern)) return true;
        if (!!user.role.toLowerCase().match(filterPattern)) return true;
        return false;
      });
    },
  },
  methods: {
    copyText: async function (text){
      try {
      await navigator.clipboard.writeText(text);
      this.$toaster.success(text + ' copied to clipboard');
      } catch (err) {
        this.$toaster.success('Failed to copy: ', err);
      }
    },

    Download: function () {
      const data = this.$data.users_filtered;
      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: "a4",
        margins: {
          top: 10,
          bottom: 10,
          left: 10,
          right: 10,
        },
      });

      const totalColumns = 2;
      const columnWidth = doc.internal.pageSize.width / totalColumns;
      const startX = doc.internal.pageSize.width / 12;
      let currentX = startX;
      let currentY = 30;
      let requiredHeight = 0;

      const fontSize = 8;
      doc.setFontSize(fontSize);

      data.forEach((item, index) => {

        const columnIndex = index % totalColumns;
        currentX = startX + columnIndex * columnWidth;
        
        const textLines = [];
        textLines.push(`Nome: ${item.name}`);
        textLines.push(`Email: ${item.email}`);
        textLines.push(`Country: ${item.country}`);
        textLines.push(`Role: ${item.role}`);
        textLines.push(`Group: ${item.group.name}`);
        textLines.push('------------');

        const lineHeight = fontSize;
        const totalLines = textLines.reduce((total, line) => total + doc.splitTextToSize(line, columnWidth).length, 0);

        requiredHeight = lineHeight * totalLines;

        if (currentY + requiredHeight > doc.internal.pageSize.height) {
          doc.addPage();
          currentX = startX;
          currentY = 30;
        }

        let startY = currentY;
        textLines.forEach((line) => {
          const lines = doc.splitTextToSize(line, columnWidth);
          doc.text(currentX, startY, lines);
          startY += (lines.length * lineHeight) - 2;
        });

        if (columnIndex !== 0) {
          currentY += requiredHeight - 10;
        }

      });

      doc.save("contacts.pdf");
    },

    handleContactInfo: function (index) {
      this.$data.user_name = this.$data.users_filtered[index].name;
      this.$data.contact_info = this.$data.users_filtered[index].contacts;
    },

    FormatDate: function (date) {
      var d = new Date(date);
      var month = (d.getUTCMonth() + 1).toString();
      var day = d.getUTCDate().toString();
      var year = d.getUTCFullYear().toString();
      var hours = d.getUTCHours().toString();
      var minutes = d.getUTCMinutes().toString();
      var seconds = d.getUTCSeconds().toString();

      month = month.length < 2 ? "0" + month : month;
      day = day.length < 2 ? "0" + day : day;
      hours = hours.length < 2 ? "0" + hours : hours;
      minutes = minutes.length < 2 ? "0" + minutes : minutes;
      seconds = seconds.length < 2 ? "0" + seconds : seconds;

      return (
        month +
        "/" +
        day +
        "/" +
        year +
        " " +
        hours +
        ":" +
        minutes +
        ":" +
        seconds
      );
    },
    
    Exclude: function (id, index) {
      var self = this;

      this.$http.delete("/api/user/" + id).then(function (e) {
        $("#modal-" + index).modal("hide");

        if (e.body.success) {
          self.$data.users.splice(index, 1);
          self.$toaster.success(e.body.success);
          return;
        }

        self.$toaster.error(e.body.error);
      });
    },

    getUsers: async function (){
      try {
        const response = await this.$http.get("/api/users");
        this.users = this.users_filtered = response.body;
        this.loading = false;
      } catch (error){
        throw error;
      }
    }
  },
  mounted: async function () {
    this.loading = true;
    await this.getUsers();
  },
};
</script>