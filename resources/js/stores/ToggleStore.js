import { defineStore } from 'pinia';

export let useToggleStore = defineStore('toggle', {
    state() {
        return {
            collapsed: true
        }
    },
    actions: {
        toggleClick() {
            if (this.collapsed == true) {
                document.body.classList.remove("is-collapsed");
                this.collapsed = false;
            } else {
                document.body.classList.add("is-collapsed");
                this.collapsed = true;
            }
        }
    },
});