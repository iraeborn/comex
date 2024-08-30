export default {

    methods: {

        formatPrice: function (value, currency) {

            let value_fixed = parseFloat(value).toFixed(3);
            let [value_decimal, value_fraction] = value_fixed.split(/\./);

            value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

            value_fixed = value_decimal + '.' + value_fraction;

            if (currency == 'USD') {
                return this.addDollarPrefix(value);
            }

            return this.addRealPrefix(value);

        },

        addDollarPrefix: function (value) {

            return `US$ ${value}`;
        },

        addRealPrefix: function (value) {

            return `R$ ${value}`;
        }

    },

}
