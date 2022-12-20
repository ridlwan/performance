<template>
    <div class="d-flex justify-content-end">
        <nav>
            <ul class="pagination">
                <li v-for="(link, index) in links" :key="index" class="page-item" :class="{ disabled: !link.url, active: link.active }">
                    <Link :href="link.url" class="page-link">
                        <span v-if="index == 0">
                            Previous
                        </span>
                        <span v-else-if="index == links.length - 1">
                            Next
                        </span>
                        <span v-else>
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
