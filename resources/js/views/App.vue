<template>
    <div class="app">
        <popcorn-modal></popcorn-modal>
        <popcorn-header :user="user"></popcorn-header>

        <div class="app-body">

            <router-view name="sidebar"></router-view>

            <main class="main">


                <popcorn-breadcrumb></popcorn-breadcrumb>


                <transition name="slide-fade" mode="out-in" duration="500" ref="content">
                    <router-view :current_user="user" :roles="roles"></router-view>
                </transition>



            </main>

        </div>

        <popcorn-footer></popcorn-footer>
    </div>
</template>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script>
    export default {
        props: ['user', 'roles'],
        mounted: function () {
            if (this.$props.user.role_list.indexOf('Admin') != -1 || this.$props.user.role_list.indexOf('Exporter') !== -1) {
                this.$router.addRoutes(window.adminRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Fumigation Agency') != -1) {
                this.$router.addRoutes(window.certificationFumigationRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('CERTIFICATE OF QUALITY') != -1) {
                this.$router.addRoutes(window.certificationQualityRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('CERTIFICATE OF WEIGHT') != -1) {
                this.$router.addRoutes(window.certificationWeightRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Insurance Agency') != -1) {
                this.$router.addRoutes(window.certificationSecurityRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Inspection Agency') != -1) {
                this.$router.addRoutes(window.inspectionAgencyRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Forwarding Agent') != -1) {
                this.$router.addRoutes(window.forwardingAgentRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Terminal Agent') != -1) {
                this.$router.addRoutes(window.terminalRoutes)
                return
            }

            if (this.$props.user.role_list.indexOf('Railway Agent') != -1) {
                this.$router.addRoutes(window.railwayRoutes)
                return
            }

            this.$router.addRoutes(window.userRoutes)

        }
    }
</script>

<style>

/* THEMES */
:root {
    --gray-dark: #1c1c1c;
    --gray-medium-dark: #2a2a2a;
    --gray-medium: #303030;
    --gray-light: #d3d3d3;
    --gray-lighter: #e3e3e3;
    --gray-white: #ffffff;

    --blue-dark: #191b57;
    --blue-medium-dark: #212e6c;
    --blue-medium: #2d3194;
    --blue-light: #656691;
    --blue-lighter: #7a89e1;
    --blue-white: #ffffff;

    --green-dark: #1d4915;
    --green-medium-dark: #7ccc6e;
    --green-medium: #addc88;
    --green-light: #deedad;
    --green-lighter: #f0f3f5;
    --green-white: #ffffff;
}

:root {
--body-background-color:#e4e5e6;
--bg-color: #e4e5e6;
--color: #2d57a6;
--text-color: #000000;

--card-header-bg: #addc88;
--card-background: #ffffff;
--card-body-background-color: #addc88;

--separator-color: #dddddd;
--separator-background-color: #dddddd;

--sidebar: #7ccc6e;
--tab-content: #fff;
--tab-content-border: #c8ced3;

--breadcrumb: #fff;
--breadcrumb-item-active: #2d3194;
--breadcrumb-item: #2d57a6;

--dropdown-menu-background-color:#e4e5e6;
--dropdown-menu-border-color: #fff;
--dropdown-menu-item-color: #2d57a6;

--dropdown-item-focus:#f0f3f5;
--dropdown-item-color-hover:#ffffff;

--dropdown-item-background-color-hover: #fff;
--dropdown-submenu-sub-toogle-after: #2d57a6;

--edit-button-color:#2d57a6;
--edit-button-color-hover:#191b57;

--badge-color: #2d57a6;

--border-bottom: 1px solid #c8ced3;
--app-header-border-bottom:  1px solid #c8ced3;

--btn-primary-heading-color: #ffffff;
--btn-primary-heading-background: #242779;
--btn-primary-heading-border-color: #242779;

--btn-primary-color: #ffffff;
--btn-primary-background: #2d3194;
--btn-primary-border: #2d3194;
--btn-primary-hover: #191b57;

--btn-secondary-btn-secondary-dropdown-toggle: #23282c;

--btn-success-color: #fff;
--btn-success-background: #38c172;
--btn-success-border: #38c172;
--btn-success-hover: #191b57;

 /* nav */
--nav-left-tab-content-border: 1px solid #7ccc6e;
--nav-active-border-color: #7ccc6e;
--nav-active-color: #7ccc6e;
--nav-active-background-color: #fff;

--nav-link-background-active: #2d3194;
--nav-link_active-focus-border: #deedad;
--nav-link-background-border: #7ccc6e #7ccc6e transparent #7ccc6e;

--nav-link-border-color-hover: #e8e8e8;
--nav-link-background-hover: #e8e8e8;
--nav-link-border-right-color: #7ccc6e;

--nav-link-alt-background: #fff;
--nav-link-alt-background-hover: #fff;

--nav-link-alt-background-border: #fff #fff transparent #fff;
--nav-link-alt-background-border-hover: #addc88 #addc88 transparent #addc88;

/* form control*/
--form-control-color: #5c6873;
--form-control-background: #fff;
--form-control-border: #e4e7ea;

--form-input-group-text-background-color:#f0f3f5;
--form-input-group-text-border:#e4e7ea;

--form-control-box-shadow-focus:rgba(173, 220, 136, .25);
--form-control-background-focus: #fff;
--form-control-border-color-focus:#addc88;
--form-control-color-focus: #2d57a6;

--message-background-color:#fff;

--avatar-background-color: #addc88;
--avatar-icon-color: #fff;
--avatar-border: #7ccc6e;

--table-line-highlight-background-color:#addc88;
--table-line-title-background-color:#f1f1f1;
--table-line-border: 1px solid rgb(202, 202, 202);

--sub-table-div-border: 1px solid rgb(202, 202, 202);
--sub-table-div-background-color:rgb(250, 250, 250);
--sub-table-nth-child-background-color: rgb(241, 241, 241);

--multiselect-color: #2d57a6;
--multiselect-tags-border: 1px solid #e8e8e8;
--multiselect-tags-background: #fff;

--multiselect-option-highlight-background: #41b883;
--multiselect-option-highlight-color: #fff;
--multiselect-content-wrapper-background: #fff;
--multiselect-content-wrapper-border: 1px solid #e8e8e8;

--multiselect-content-scrollbar-track: #addc88;
--multiselect-content-scrollbar-thumb-background: #7ccc6e;
--multiselect-content-scrollbar-background-hover: #555;
}

[data-theme='dark'] {
    --body-background-color: #2e2e2e;
    --bg-color: #1c1c1c;
    --color: #ffffff;
    --text-color: #d3d3d3;

    --card-header-bg: #2a2a2a;
    --card-background: #1c1c1c;
    --card-body-background-color: #2a2a2a;

    --separator-color: #2a2a2a;
    --separator-background-color: #2a2a2a;

    --table-background: #303030;
    --table-border-top: #2a2a2a;

    --sidebar: #2a2a2a;
    --tab-content: #303030;
    --tab-content-border: #303030;

    --breadcrumb: #303030;
    --breadcrumb-item: #656565;
    --breadcrumb-item-active: #ffffff;

    --dropdown-menu-background-color: #2a2a2a;
    --dropdown-menu-border-color: #303030;
    --dropdown-menu-item-color: #ffffff;

    --dropdown-item-focus: #303030;
    --dropdown-item-color-hover: #ffffff;

    --dropdown-item-background-color-hover: #4a4a4a;
    --dropdown-submenu-sub-toogle-after: #ffffff;

    --edit-button-color: #ffffff;
    --edit-button-color-hover: #d3d3d3;

    --badge-color: #303030;

    --border-bottom: 0;
    --app-header-border-bottom: 0;

    --btn-primary-heading-color: #ffffff;
    --btn-primary-heading-background: #2a2a2a;
    --btn-primary-heading-border-color: #2a2a2a;

    --btn-primary-color: #ffffff;
    --btn-primary-background: #2a2a2a;
    --btn-primary-border: #2a2a2a;
    --btn-primary-hover: #4a4a4a;

    --btn-secondary-btn-secondary-dropdown-toggle: #2a2a2a;

    --btn-success-color: #fff;
    --btn-success-background: #3a3a3a;
    --btn-success-border: #3a3a3a;
    --btn-success-hover: #2a2a2a;

    /* nav */
    --nav-left-tab-content-border: 1px solid #4a4a4a;
    --nav-active-border-color: #4a4a4a;
    --nav-active-color: #4a4a4a;
    --nav-active-background-color: #2a2a2a;

    --nav-link-background-active: #4a4a4a;
    --nav-link_active-focus-border: #2a2a2a;
    --nav-link-background-border: #2a2a2a #2a2a2a transparent #4a4a4a;

    --nav-link-border-color-hover: #4a4a4a;
    --nav-link-background-hover: #363636;
    --nav-link-border-right-color: #363636;

    --nav-link-alt-background: #303030;
    --nav-link-alt-background-hover: #303030;

    --nav-link-alt-background-border: #2a2a2a #2a2a2a transparent #4a4a4a;
    --nav-link-alt-background-border-hover: #2a2a2a #2a2a2a transparent #2a2a2a;

    /* form control*/
    --form-control-color: #ffffff;
    --form-control-background: #303030;
    --form-control-border: #4a4a4a;

    --form-input-group-text-background-color: #2a2a2a;
    --form-input-group-text-border: #4a4a4a;

    --form-control-box-shadow-focus: rgba(42, 42, 42, .25);
    --form-control-background-focus: #4a4a4a;
    --form-control-border-color-focus: #2a2a2a;
    --form-control-color-focus: #fff;

    --message-background-color: #303030;

    --avatar-background-color: #2a2a2a;
    --avatar-icon-color: #fff;
    --avatar-border: #303030;

    --table-line-highlight-background-color: #303030;
    --table-line-title-background-color: #2a2a2a;
    --table-line-border: 1px solid #2a2a2a;

    --sub-table-div-border: 1px solid #2a2a2a;
    --sub-table-div-background-color: #2a2a2a;
    --sub-table-nth-child-background-color: rgb(241, 241, 241);

    --multiselect-color: #fff;
    --multiselect-tags-border: 1px solid #4a4a4a;
    --multiselect-tags-background: #303030;

    --multiselect-option-highlight-background: #303030;
    --multiselect-option-highlight-color: #fff;
    --multiselect-content-wrapper-background: #4a4a4a;
    --multiselect-content-wrapper-border: 1px solid #303030;

    --multiselect-content-scrollbar-track: #4a4a4a;
    --multiselect-content-scrollbar-thumb-background: #2a2a2a;
    --multiselect-content-scrollbar-background-hover: #555;
}

[data-theme='blue-dark'] {
    --body-background-color: #2e3763;
    --bg-color: #121334;
    --color: #ffffff;
    --text-color: #e3e3e3;

    --card-header-bg: #191b57;
    --card-background: #121334;
    --card-body-background-color: #121334;
    
    --separator-color: #121334;
    --separator-background-color: #121334;

    --table-background: #212e6c;
    --table-border-top: #191b57;

    --sidebar: #191b57;
    --tab-content: #212e6c;
    --tab-content-border: #212e6c;

    --breadcrumb: #212e6c;
    --breadcrumb-item: #656691;
    --breadcrumb-item-active: #ffffff;

    --dropdown-menu-background-color:#191b57;
    --dropdown-menu-border-color: #212e6c;
    --dropdown-menu-item-color: #ffffff;

    --dropdown-item-focus:#212e6c;
    --dropdown-item-color-hover:#ffffff;

    --dropdown-item-background-color-hover: #2e3763;
    --dropdown-submenu-sub-toogle-after: #ffffff;

    --edit-button-color:#ffffff;
    --edit-button-color-hover:#e3e3e3;

    --badge-color: #212e6c;

    --border-bottom: 0;
    --app-header-border-bottom: 0;

    --btn-primary-heading-color: #ffffff;
    --btn-primary-heading-background: #191b57;
    --btn-primary-heading-border-color: #191b57;

    --btn-primary-color: #ffffff;
    --btn-primary-background: #191b57;
    --btn-primary-border: #191b57;
    --btn-primary-hover: #2d3194;

    --btn-secondary-btn-secondary-dropdown-toggle: #191b57;

    --btn-success-color: #fff;
    --btn-success-background: #242779;
    --btn-success-border: #242779;
    --btn-success-hover: #191b57;

    /* nav */
    --nav-left-tab-content-border: 1px solid #2d3194;
    --nav-active-border-color: #2d3194;
    --nav-active-color: #2d3194;
    --nav-active-background-color: #191b57;

    --nav-link-background-active: #2d3194;
    --nav-link_active-focus-border: #191b57;
    --nav-link-background-border: #191b57 #191b57 transparent #2d3194;

    --nav-link-border-color-hover: #2d3194;
    --nav-link-background-hover: #232b51;
    --nav-link-border-right-color: #232b51;

    --nav-link-alt-background: #212e6c;
    --nav-link-alt-background-hover: #212e6c;

    --nav-link-alt-background-border: #191b57 #191b57 transparent #2d3194;
    --nav-link-alt-background-border-hover: #191b57 #191b57 transparent #191b57;

    /* form control*/
    --form-control-color: #ffffff;
    --form-control-background:  #212e6c;
    --form-control-border: #2d3194;

    --form-input-group-text-background-color:#191b57;
    --form-input-group-text-border:#2d3194;

    --form-control-box-shadow-focus:rgba(25, 27, 87, .25);
    --form-control-background-focus: #2d3194;
    --form-control-border-color-focus:#191b57;
    --form-control-color-focus: #fff;

    --message-background-color:#212e6c;

    --avatar-background-color: #191b57;
    --avatar-icon-color: #fff;
    --avatar-border: #212e6c;

    --table-line-highlight-background-color:#212e6c;
    --table-line-title-background-color:#191b57;
    --table-line-border: 1px solid #191b57;

    --sub-table-div-border: 1px solid #191b57;
    --sub-table-div-background-color:#191b57;
    --sub-table-nth-child-background-color: rgb(241, 241, 241);

    --multiselect-color: #fff;
    --multiselect-tags-border: 1px solid #2d3194;
    --multiselect-tags-background: #212e6c;

    --multiselect-option-highlight-background: #212e6c;
    --multiselect-option-highlight-color: #fff;
    --multiselect-content-wrapper-background: #2d3194;
    --multiselect-content-wrapper-border: 1px solid #212e6c;

    --multiselect-content-scrollbar-track: #2e3763;
    --multiselect-content-scrollbar-thumb-background: #191b57;
    --multiselect-content-scrollbar-background-hover: #555;
}


body {
    background-color: var(--body-background-color) !important;
    color: var(--color) !important;
}

.table {
    color: var(--color) !important;
}

.table-hover tbody tr:hover {
  color: var(--text-color);
  background-color: var(--table-background);
}

.table-containers .table td, .table-containers .table th {
    border-top: 1px solid var(--table-border-top) !important;
    background-color: var(--table-background) !important;
}

.message {
    background-color: var(--message-background-color) !important;
}

.message_content {
    justify-content: unset !important;
    min-height: 0 !important;
}

/* END THEMES */

    .multiselect {
        color: var(--multiselect-color) !important;
    }

    .multiselect__tag {
        margin: 5px !important;
    }

    /* .multiselect__tags-wrap {
        display: flex !important;
    } */

    .multiselect__tags {
        border: var(--multiselect-tags-border) !important;
        background: var(--multiselect-tags-background) !important;
    }

    .multiselect__input, .multiselect__single {
        background: var(--multiselect-tags-background) !important;
    }

    .multiselect__option--highlight {
        background: var(--multiselect-option-highlight-background) !important;
        color: var(--multiselect-option-highlight-color) !important;
    }

    .multiselect__content-wrapper {
        background: var(--multiselect-content-wrapper-background) !important;
        border: var(--multiselect-content-wrapper-border) !important;
        overflow-x: hidden !important;
        overflow-y:auto !important;
    }

    .multiselect__content-wrapper::-webkit-scrollbar {
        width: 10px !important;
    }

    .multiselect__content-wrapper::-webkit-scrollbar-track {
        background: var(--multiselect-content-scrollbar-track) !important;
    }

    .multiselect__content-wrapper::-webkit-scrollbar-thumb {
    background: var(--multiselect-content-scrollbar-thumb-background);
    border-radius: 10px;
    }

    .multiselect__content-wrapper::-webkit-scrollbar-thumb:hover {
    background: var(--multiselect-content-scrollbar-background-hover);
    margin: 2px;
    }

    .multiselect__option--selected {
    background: #f3f3f3;
    color: #35495e;
    font-weight: 700;
}

    .fc-daygrid-event {
        white-space: normal !important;
    }
    .avatar {
        display: flex;
        height: 40px !important;
        width: 40px !important;
        background-color: var(--avatar-background-color) !important;
        border-radius: 30px;
        padding: 0 !important;
        border: 1px solid var(--avatar-border);
    }

    .avatar i {
        margin: 11px;
    }

    .avatar i:before {
        font-size: 15px;
        color: var(--avatar-icon-color);
    }

    .board[data-v-48354f6b] {
        height: auto !important;
        overflow-y: hidden !important;
    }

    .btn-primary {
        color: var(--btn-primary-color);
        background-color: var(--btn-primary-background);
        border-color: var(--btn-primary-border);
    }

    .btn-primary:hover {
        color: var(--btn-primary-color);
        background-color: var(--btn-primary-hover);
        border-color: var(--nav-link-background-active);
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.2rem var(--btn-primary-heading-border-color) !important;
    }

    .btn-primary-heading {
        color: var(--btn-primary-heading-color);
        background-color: var(--btn-primary-heading-background);
        border-color: var(--btn-primary-heading-border-color);
    }

    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: var(--btn-primary-color);
        background-color: var(--btn-primary-background);
        border-color: var(--btn-primary-border);
    }

    .btn-secondary.focus, .btn-secondary:focus {
        box-shadow: none !important;
    }

    .btn-secondary, .btn-secondary.dropdown-toggle {
        color: var(--btn-secondary-btn-secondary-dropdown-toggle) !important;
        /* background-color: #acb5bc;
        border-color: #a5aeb7; */
    }

    .dropdown-item:first-child {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }

    .dropdown-menu {
        background-color: var(--dropdown-menu-background-color) !important;
        border-color: var(--dropdown-menu-border-color) !important;
    }

    .dropdown-item {
        color: var(--dropdown-menu-item-color) !important;
        border-bottom: 1px solid var(--dropdown-menu-border-color) !important;
    }

    .dropdown-item:hover {
        color: var(--dropdown-item-color-hover);
        background-color: var(--dropdown-item-background-color-hover) !important;
    }

    .dropdown-item:focus, .dropdown-item:hover {
        background-color: var(--dropdown-item-background-color-hover) !important;
    }

    .dropdown-submenu>#sub-toogle:after {
        border-left-color: var(--dropdown-submenu-sub-toogle-after) !important;
    }

    .edit-button,
    .edit-button:focus,
    .edit-button:focus-visible,
    .edit-button:hover {
        border: none;
        background-color: rgba(255, 255, 255, 0);
        outline: -webkit-focus-ring-color auto 0;
    }
    .edit-button i {
        color: var(--edit-button-color);
    }
    .edit-button,i:hover {
        color: var(--edit-button-color-hover);
    }

    .badge {
        color: var(--badge-color) !important;
    }

    .btn-success {
        color: var(--btn-primary-color);
        background-color: var(--btn-primary-background);
        border-color: var(--btn-primary-border);
    }

    .btn-success:hover {
    color: var(--btn-primary-color);
    background-color: var(--btn-success-hover);
    border-color: var(--btn-success-border) !important;
    }

    .btn-success:focus,
    .btn-success.focus {
    color: #fff;
    background-color: #2fa360;
    border-color: #2d995b;
    box-shadow: 0 0 0 0.2rem rgba(86, 202, 135, 0.5);
    }

    .btn-success.disabled,
    .btn-success:disabled {
    color: #fff;
    background-color: #38c172;
    border-color: #38c172;
    }

    .btn-success:not(:disabled):not(.disabled):active,
    .btn-success:not(:disabled):not(.disabled).active,
    .show > .btn-success.dropdown-toggle {
    color: #fff;
    background-color: #2d995b;
    border-color: #2a9055;
    }

    .btn-success:not(:disabled):not(.disabled):active:focus,
    .btn-success:not(:disabled):not(.disabled).active:focus,
    .show > .btn-success.dropdown-toggle:focus {
    box-shadow: 0 0 0 0.2rem rgba(86, 202, 135, 0.5);
    }

    /* body {
        color: #2d57a6;
        font-family: "open sans", sans-serif
    } */

    .slide-fade-enter-active {
        transition: all .3s;
    }

    .slide-fade-leave-active {
        transition: all .8s;
    }

    .slide-fade-enter,
    .slide-fade-leave-to

    /* .slide-fade-leave-active em versões anteriores a 2.1.8 */
        {
        transform: translateX(10px);
        opacity: 0;
    }

    .slide-fade {
        position: relative;
        top: 0;
    }

    .form-control {
        color: var(--form-control-color);
        background-color: var(--form-control-background);
        background-clip: padding-box;
        border: 1px solid var(--form-control-border);
    }

    /* .form-control:disabled,
    .form-control[readonly] {
        background-color: #addc88;
    }

    .form-control {
        border-color: #7ccc6e;
    } */

    .form-control:focus {
        color: var(--form-control-color-focus) !important;
        background: var(--form-control-background-focus);
        border-color: var(--form-control-border-color-focus) !important;
        outline: 0;
        box-shadow: 0 0 0 0.2rem var(--form-control-box-shadow-focus) !important;
    }

    .form-control option:hover {
        background: #addc88 !important;
    }

    .input-group-text {
        background-color: var(--form-input-group-text-background-color);
        border: 1px solid var(--form-input-group-text-border);
    }

    .select2-dropdown,
    .select2-container--default .select2-search--dropdown .select2-search__field,
    .select2-container--default .select2-selection--single,
    .card-header,
    .card-footer,
    .card {
        border-color: var(--card-header-bg);
    }

    /* .sidebar .nav-link.active {
        background-color: var(--nav-link-background-active) !important;
    } */

    .card-header .nav-tabs {
        margin-bottom: -0.3rem !important;
    }

    .nav-tabs .nav-link-alt.active {
        color: var(--text-color);
        background-color: var(--nav-link-alt-background);
        border: 1px solid var(--nav-link-alt-background-border);
    }

    .nav-tabs .nav-link-alt:hover {
        cursor: pointer;
        text-decoration: none;
        border-color: var(--nav-link-alt-background-border-hover);
        background-color: var(--nav-link-alt-background-hover);
    }

    .nav-link-alt {
        display: block;
        padding: .6rem 1rem;
    }

    .nav-tabs .nav-link-alt {
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        color: #73818f;
    }

    .breadcrumb {
        background-color: var(--breadcrumb);
        border-bottom: var(--border-bottom);
    }

    .breadcrumb-item.active,
    .sidebar .nav-link.active i.nav-icon {
        color: var(--breadcrumb-item-active) !important;
    }

    .breadcrumb-item a {
        color: var(--breadcrumb-item) !important;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        font-weight: bold !important;
    }

    .sidebar .nav-dropdown.open {
        background-color: transparent;
    }

    .card {
        background-color: var(--tab-content) !important;
    }

    .card-header {
        padding: 0.75rem 1.25rem 0.2rem;
    }

    .card-header,
    .card-footer,
    .app-header.navbar,
    .app-footer {
        background-color: var(--card-header-bg);
    }

    .card-columns .card .card-body {
        background-color: var(--card-body-background-color);
    }

    .separator {
        margin-top: 0;
        margin-bottom: 0;
        width: 100%;
        color: var(--separator-color);
        border: none;
        height: 1px;
        background-color: var(--separator-background-color);
    }

    .app-header {
        border-bottom: var(--app-header-border-bottom) !important;
    }

    .sidebar {
        background-color: var(--sidebar);
    }

    .sidebar-minimized .sidebar .sidebar-minimizer {
        background-color: rgba(36, 40, 44, .2);
    }

    .sidebar .nav-link:hover {
        background-color: #2D57A6;
    }

    .sidebar .nav-link {
        /*color: #2D57A6;*/
        background-color: #000;
        margin: 15px;
        margin-bottom: 0px;
        padding-top: 5px;
        padding-bottom: 5px;
        background-color: #2d3194;
    }

    .nav-tabs .nav-item {
        margin-right: 0px;
    }

    li.nav-item.active {
        margin-right: 0px;
    }

    .sidebar .nav-link .nav-icon {
        color: #fff;
    }

    body .sidebar .nav-link:hover .nav-icon {
        color: #fff;
    }

    .dropdown-menu {
        background-color: #deedad;
        border-color: #addc88;
    }

    .dropdown-item:focus,
    .dropdown-item:hover {
        background-color: #addc88;
    }

    .pendence {
        background-color: #f86c6b !important;
    }

    .pendence:hover {
        background-color: #f64846 !important;
        border-color: #f63c3a !important;
    }

    .dropdown-header {
        background: #7ccc6e;
        border-bottom: 1px solid #7ccc6e;
    }

    .dropdown-item i {
        color: #7ccc6e;
    }

    .dropdown-header {
        color: #2D57A6;
    }

    .app-header .d-md-down-none .nav-item .nav-link {
        padding: 5px 10px;
        background-color: #2d3194;
        color: #fff;
    }

    .sidebar .nav-link.active a {
        padding: 5px 10px;
        background-color: #2d3194;
        color: #fff;
        display: block;
    }

    .spacer {
        background-color: #fff;
    }


    table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid var(--btn-success-border) !important;
    }

    .table tbody+tbody {
        border-top: 1px solid var(--btn-success-border) !important;
    }

    .table-container th,
    .table-containers th {
        border-top: 5px solid #fef101;
    }

    .table-container td,
    .table-containers td {
        border-top: 3px solid #fef101;
    }

    .fc-direction-ltr .fc-daygrid-event.fc-event-end,
    .fc-event-main-frame {
        flex-direction: column;
    }

    .fc-event-desc,
    .fc-event-title {
        white-space: break-spaces;
    }

    .fc .fc-button-primary {
        background-color: #7ccc6e;
        border-color: #7ccc6e;
    }

    .fc .fc-button-primary:hover {
        background-color: #7ccc6e;
        border-color: #7ccc6e;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active {
        background-color: #7ccc6e;
        border-color: #7ccc6e;
    }

    .fc .fc-button-primary:disabled {
        background-color: #7ccc6e;
        border-color: #7ccc6e;
    }
</style>

<style>

.table-line-highlight {
    background-color: var(--table-line-highlight-background-color);
}

.table-line-title {
    background-color: var(--table-line-title-background-color);
}

.table-line div {
    border: var(--table-line-border);
}

.sub-table > div {
    border: var(--sub-table-div-border);
    background-color: var(--sub-table-div-background-color);
}

.sub-table:nth-child(odd) {
    background-color: var(--sub-table-nth-child-background-color);
}

.tab-content {
    margin-top: -1px;
    background: var(--tab-content);
    border: 1px solid var(--tab-content-border);
}

.nav-left .page-container {
    display: flex;
}

.nav-left .tab-content {
    width: 100%;
    margin-top: 0;
    margin-left: 3px;
    margin-bottom: -1px;
    border: var(--nav-left-tab-content-border)
}

.nav-left .nav.nav-tabs {
    z-index: 1;
    float: left;
    display: block;
    padding-right: 0;
    border-bottom: 0;
    margin-right: -5px;
    border-right: 1px solid transparent;
}

.nav-tabs .nav-item .nav-link.active,
.nav-tabs .nav-item .nav-link.active:hover {
    color: var(--color);
    font-weight: bold;
    border-right-width: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-top-left-radius: .25em;
    border-bottom-left-radius: .25em;
    border-color: var(--nav-active-border-color);
    background-color: var(--tab-content);
}

.nav-tabs .nav-item .nav-link:hover {
    border-color: var(--nav-link-border-color-hover);
    border-right-width: 1px;
    background-color: var(--nav-link-background-hover);
    border-right-color: var(--nav-link-border-right-color);
}


</style>