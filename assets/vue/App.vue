<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <router-link class="navbar-brand" to="/">Vse Instruments</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                    <router-link class="nav-item" tag="div" to="/add" active-class="active">
                        <a class="btn">Create delivery</a>
                    </router-link>
            </div>
        </nav>

        <router-view></router-view>
    </div>
</template>

<script>
  export default {
    name: 'app',
  }
</script>