<template>
  <div class="sidebar">
    <nav class="sidebar-nav">

      <div id="accordion" class="p-2">

        <div class="d-flex justify-content-center">
          <router-link :to="{ name: 'Dashboard' }" class="btn btn-primary w-100 m-1" data-toggle="collapse"
            data-target="#collapseDashboard">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-speedometer"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Dashboard</dd>
            </dl>
          </router-link>
        </div>

        <div id="collapseDashboard" class="hide" data-parent="#accordion"></div>

        <div id="headingFinanceiro" class="d-flex justify-content-center">
          <a class="btn btn-primary w-100 m-1" href="#" data-toggle="collapse" data-target="#collapseFinanceiro"
            aria-controls="collapseUsers">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-user"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Financial</dd>
            </dl>
          </a>
        </div>

        <div id="collapseFinanceiro"
          :class="['/transactions', '/transactions/report'].includes(this.$route.path) ? 'collapse show' : 'collapse hide'"
          data-parent="#accordion">

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Transactions', query: { status: 'all' } }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-speedometer"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Transactions</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Transactions Report' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-speedometer"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Report</dd>
              </dl>
            </router-link>
          </div>

        </div>

        <div id="headingUsers" class="d-flex justify-content-center">

          <a class="btn btn-primary w-100 m-1" href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-controls="collapseUsers">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-user"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Users</dd>
            </dl>
          </a>

        </div>

        <div id="collapseUsers"
          :class="['/users', '/user/new'].includes(this.$route.path) ? 'collapse show' : 'collapse hide'"
          aria-labelledby="users" data-parent="#accordion">

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Users' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-people"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">All Users</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'New user' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-user-follow"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">New User</dd>
              </dl>
            </router-link>
          </div>

        </div>

        <div class="d-flex justify-content-center">
          <router-link :to="{ name: 'Calendar' }" class="btn btn-primary w-100 m-1" data-toggle="collapse"
            data-target="#collapseDashboard">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-calendar"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Calendar</dd>
            </dl>
          </router-link>
        </div>
          
        <div id="headingOrders" class="d-flex justify-content-center">

          <a class="btn btn-primary w-100 m-1" href="#" data-toggle="collapse" data-target="#collapseOrders"
            aria-controls="orders">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-user"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Orders</dd>
            </dl>
          </a>

        </div>

        <div id="collapseOrders"
          :class="['/order', '/order/reports', '/order/details', '/order/railway', '/order/containers', '/order/new', '/order/info'].some(element => this.$route.fullPath.includes(element)) ? 'collapse show' : 'collapse hide'"
          aria-labelledby="orders" data-parent="#accordion">

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Orders' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-folder-alt"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">All Orders</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Order Reports' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-doc"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Reports</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Details Orders' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-truck-moving"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Truck Loading</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Railway Orders' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-train"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Railway</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Order Containers' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-boxes"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Containers</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'New order' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-plus"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">New Order</dd>
              </dl>
            </router-link>
          </div>
        </div>

        <div id="headingRegisters" class="d-flex justify-content-center">

          <a class="btn btn-primary w-100 m-1" href="#" data-toggle="collapse" data-target="#collapseRegisters"
            aria-controls="registers">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-user"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Registers</dd>
            </dl>
          </a>

        </div>

        <div id="collapseRegisters"
          :class="['/banks/new', '/banks', '/document-order', '/document-order/new', '/drivers', '/drivers/new', '/signatures', '/groups', '/groups/new', '/services', '/services/new', '/specifications', '/specifications/new'].includes(this.$route.path) ? 'collapse show' : 'collapse hide'"
          aria-labelledby="registers" data-parent="#accordion">

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Banks' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-wallet"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Banks</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'DocumentOrders' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-docs"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Documents</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Drivers' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-truck"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Drivers</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Signatures' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-university"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Signatures</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Groups' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fas fa-users"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Groups</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Services' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon fa fa-th-list"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Services</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Specifications' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-equalizer"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Specifications</dd>
              </dl>
            </router-link>
          </div>

        </div>

        <div id="headingContracts" class="d-flex justify-content-center">

          <a class="btn btn-primary w-100 m-1" href="#" data-toggle="collapse" data-target="#collapseContracts"
            aria-controls="contracts">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-book-open"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Contracts</dd>
            </dl>
          </a>

        </div>

        <div id="collapseContracts"
          :class="['/contracts', '/contracts/new', '/provisions', '/contracts/reort'].includes(this.$route.path) ? 'collapse show' : 'collapse hide'"
          aria-labelledby="contracts" data-parent="#accordion">

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Contracts' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-organization"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">All Contracts</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'New Contract' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-plus"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">New Contract</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Provisions' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-briefcase"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">All Provisions</dd>
              </dl>
            </router-link>
          </div>

          <div class="d-flex justify-content-center ml-3">
            <router-link :to="{ name: 'Contracts Report' }" class="btn btn-primary w-100 m-1">
              <dl class="row m-0">
                <dt class="col-3 p-0 m-0"><i class="nav-icon icon-doc"></i></dt>
                <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Contracts Report</dd>
              </dl>
            </router-link>
          </div>

        </div>

        <div class="d-flex justify-content-center">
          <router-link :to="{ name: 'DRE Operations', query: { status: 'all' } }" class="btn btn-primary w-100 m-1"
            data-toggle="collapse" data-target="#collapseDreOperations">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-speedometer"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">DRE Operations</dd>
            </dl>
          </router-link>
        </div>
        <div id="collapseDreOperations" class="collapse hide" data-parent="#accordion"></div>

        <div class="d-flex justify-content-center">
          <router-link :to="{ name: 'Invoices', query: { status: 'all' } }" class="btn btn-primary w-100 m-1"
            data-toggle="collapse" data-target="#collapdeInvoices">
            <dl class="row m-0">
              <dt class="col-3 p-0 m-0"><i class="nav-icon icon-speedometer"></i></dt>
              <dd class="col-9 p-0 m-0 text-left" v-if="!isHidden">Invoices</dd>
            </dl>
          </router-link>
        </div>
        <div id="collapdeInvoices" class="collapse hide" data-parent="#accordion"></div>

      </div>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button" v-on:click="isHidden = !isHidden"></button>
  </div>
</template>
<style scoped>
#submenu {
  display: none;
}

.show {
  display: block;
}
</style>
<script>

export default {
  data() {
    return {
      isHidden: false
    }
  }
};
</script>
