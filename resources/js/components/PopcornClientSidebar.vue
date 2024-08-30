<template>
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item">
                    <router-link :to="{ name: 'Dashboard' }" class="nav-link">
                        <i class="nav-icon icon-speedometer"></i> Dashboard
                    </router-link>

                </li>

                <li class="nav-item">
                    <router-link :to="{ name: 'My profile' }" class="nav-link">
                        <i class="nav-icon icon-user"></i> My profile
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="{ name: 'My orders' }" class="nav-link">
                        <i class="nav-icon icon-shuffle"></i> My Orders
                    </router-link>
                </li>

                <!-- <li class="nav-title">Theme</li>

                <li class="nav-item">
                    <a class="nav-link" href="colors.html">
                    <i class="nav-icon icon-drop"></i> Colors</a>
                </li>


                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icon-puzzle"></i> Base</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="base/breadcrumb.html">
                            <i class="nav-icon icon-puzzle"></i> Breadcrumb</a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </nav>

        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
</template>

<script>
export default {
  name: 'popcorn-sidebar',
  data () {
    return {}
  }
}
</script>