<template>
    <div v-if="links.length > 3" class="d-flex justify-content-center mt-2">
        <nav>
            <ul class="pagination">
                <li v-for="(link, index) in links" :key="index" class="page-item" :class="{ disabled: !link.url, active: link.active }">
                    <Link :href="link.url" class="page-link" preserve-scroll>
                        <span v-if="index == 0">
                            <i class="fa fa-angle-left"></i>
                        </span>
                        <span v-else-if="index == links.length - 1">
                            <i class="fa fa-angle-right"></i>
                        </span>
                        <span v-else :class="link.active ? 'text-white' : ''">
                            {{ link.label }}
                        </span>
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    links: Array,
});
</script>
