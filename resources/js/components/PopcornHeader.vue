<template>
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="btn btn-primary" href="/dashboard">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            <li class="nav-item px-3" v-if="IsAdmin(current_user)">
                <a class="btn btn-primary" href="/users">
                    <i class="nav-icon icon-user"></i> Users
                </a>
            </li>
            <li class="nav-item px-3" v-if="IsAdmin(current_user)">
                <a class="btn btn-primary" href="/settings">
                    <i class="nav-icon icon-settings"></i> Settings
                </a>
            </li>
        </ul>
        <ul class="nav navbar-nav d-md-down-none ml-auto">
            <li class="nav-item px-3" v-if="this.$route.path !== '/users'">
                <a href="#" @click="Download" class="btn btn-secondary">Save as PDF</a>
            </li>
        </ul>

        <ul class="nav navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="avatar" v-if="!this.$props.user.profile_picture">
                        <i class="nav-icon icon-user"></i>
                    </span>
                    <img class="img-avatar" v-else :src="this.$props.user.profile_picture" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/user/me" class="dropdown-item">
                        <i class="nav-icon icon-user"></i> Profile
                    </a>
                    <a class="dropdown-item" href="/logout">
                        <i class="nav-icon icon-logout"></i> Logout
                    </a>
                    <change-theme></change-theme>
                </div>
            </li>
        </ul>
    </header>
</template>


<script>
import jsPDF from 'jspdf'
import html2canvas from 'html2canvas'
import ChangeTheme from './ChangeTheme';

export default {
    name: 'popcorn-header',
    props: ['user'],
    components: {'change-theme': ChangeTheme},
    data() {
        return {
            current_user: this.$props.user,
        }
    },
    methods: {
        getProfilePicture: function () {
            if (this.$props.user.profile_picture)
                return this.$props.user.profile_picture

            return '/img/avatar.png';
        },
        IsAdmin: function (user) {
            for (var i in user.roles) {
                if (user.roles[i].name == "Admin") return true
            }

            return false
        },
        Download: async function () {
            const path = this.$route.path;
            let pdf = new jsPDF('', 'pt', 'a4');

            if (path === '/transactions/report') {
                document.getElementById("content").style.height = "auto";
                var currentPosition = document.getElementById("content").scrollTop;
            }

            var datetime = new Date().toLocaleString();
            let para = document.querySelector(".date-report");

            if (para !== null) {
                para.removeChild(para.firstChild);
            }

            const node = document.createTextNode("generated report " + datetime);

            if (para === null) {
                para = document.createElement("p");
                para.setAttribute("class", "date-report");
            }


            para.appendChild(node);

            const element = document.querySelector(".card-body");
            const nextDiv = document.querySelector(".card-body div");
            element.insertBefore(para, nextDiv);

            let canvasElement = document.createElement('canvas');
            let width = pdf.internal.pageSize.getWidth();

            let date = new Date();
            let newHTML = document.createElement('div');

            document.querySelectorAll(".card-body").forEach(el => {
                newHTML.appendChild(el.cloneNode(true));
            });

            document.body.appendChild(newHTML);

            html2canvas(newHTML, {
                canvas: canvasElement
            }).then(function (canvas) {
                var contentWidth = canvas.width;
                var contentHeight = canvas.height;
                var pageHeight = contentWidth / 592.28 * 841.89;
                var leftHeight = contentHeight;
                var position = 5;
                var imgWidth = 555.28;
                var imgHeight = 555.28 / contentWidth * contentHeight;
                var pageData = canvas.toDataURL('image/png', 1.0);

                if (leftHeight < pageHeight) {
                    pdf.addImage(pageData, 'JPEG', 0, 5, width, 0);
                } else {

                    while (leftHeight > 0) {
                        pdf.addImage(pageData, 'JPEG', 20, position + 20, imgWidth, imgHeight)
                        leftHeight -= pageHeight;
                        position -= 841.89;

                        if (leftHeight > 0) {
                            pdf.addPage();
                        }
                    }

                }

                pdf.save(path.replace('/', '').replace(/\//g, '_') + "_" + new Date().toLocaleDateString("en-US", { year: 'numeric', month: '2-digit', day: '2-digit', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false }) + ".pdf");
                newHTML.remove();

                if (path === '/transactions/report') {
                    para.remove();
                    document.getElementById("content").style.height = screen.height - 470;
                    document.getElementById("content").scrollTop = currentPosition;
                }
            });

        },
    }
}
</script>