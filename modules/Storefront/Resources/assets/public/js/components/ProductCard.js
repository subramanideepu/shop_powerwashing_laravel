import ProductMixin from "../mixins/ProductMixin";
import "./ProductRating";

Alpine.data("ProductCard", (product) => ({
    ...ProductMixin(product),

    get isQuoteProduct() {
    return Number(this.item.selling_price.inCurrentCurrency.amount) <= 0;
},

    get inWishlist() {
        return this.$store.state.inWishlist(this.product.id);
    },

    get inCompareList() {
        return this.$store.state.inCompareList(this.product.id);
    },
}));
