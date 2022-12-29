<template>
    <div>
        <Header>
            <template #heading>
                <slot name="heading"></slot>
            </template>
        </Header>

        <div class="border-radius-xl mt-4 mx-4 position-relative" style="background-image: url('/assets/img/pexels-max-vakhtbovych-7750123.jpg'); background-size: cover">
            <main class="main-content mt-1 border-radius-lg">
                <div class="section min-vh-85 position-relative">
                    <div class="container">
                        <div class="row pt-7">
                            <Sidenav />

                            <div class="col-lg-10 col-md-10">
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- <Footer /> -->
    </div>
</template>

<script setup>
import Header from "./Header.vue";
import Sidenav from "./Sidenav.vue";
import Footer from "./Footer.vue";
</script>
