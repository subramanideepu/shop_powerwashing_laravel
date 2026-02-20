import { trans, formatCurrency } from "./functions";
import { notify } from "./components/Toaster";
import Alpine from "alpinejs";
import jQuery from "jquery";
import * as bootstrap from "bootstrap/dist/js/bootstrap.js";
import "./vendors/axios";

window.Alpine = Alpine;
window.bootstrap = bootstrap;
window.$ = window.jQuery = jQuery;
window.trans = trans;
window.formatCurrency = formatCurrency;
window.notify = notify;

document.addEventListener("alpine:init", () => {
    Alpine.store("quote", {
        show: false,
        product: null,

        open(product) {
            this.product = product;
            this.show = true;
        },

        close() {
            this.show = false;
        },
    });
});

Alpine.data("App", () => ({

    loading: false,

    quoteForm: {
        name: "",
        phone: "",
        email: "",
        message: ""
    },
    hideOverlay() {

        const layoutStore = this.$store.layout;

        layoutStore.closeSidebarMenu();
        layoutStore.closeSidebarCart();
        layoutStore.closeSidebarFilter();
        layoutStore.closeLocalizationMenu();
    },
    async submitQuote() {

        this.loading = true;

        try {

            await axios.post(route("quote.submit"), {
                product_name: this.$store.quote.product?.name,
                product_url: this.$store.quote.product?.url,
                name: this.quoteForm.name,
                phone: this.quoteForm.phone,
                email: this.quoteForm.email,
                message: this.quoteForm.message,
            });

            notify("Quote request sent successfully 🔥");

            this.$store.quote.close();

            // Reset form
            this.quoteForm = {
                name: "",
                phone: "",
                email: "",
                message: ""
            };

        } catch (error) {

            notify("Something went wrong.");

        } finally {

            this.loading = false;

        }
    }

}));
