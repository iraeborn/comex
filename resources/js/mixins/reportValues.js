export default {
    methods: {
        ExchangeVariation: function (value, dollar_value, main_dollar_value) {
            if (!value || !dollar_value) return 0;
            return (value * dollar_value) - (value * main_dollar_value);
        },
        NFEValue: function (value, main_dollar_value) {
            return value * main_dollar_value;
        },
    },

}
