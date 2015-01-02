(function($) {
    $(function() {
        $('.continent-input').easySelect({
            idKey: 'value',
            textKey: 'name',
            items: [
                { name: 'Africa', value: 1},
                { name: 'Antarctica', value: 2},
                { name: 'Asia', value: 3},
                { name: 'Australia', value: 4},
                { name: 'Europe', value: 5},
                { name: 'North America', value: 6},
                { name: 'South America', value: 7}
            ]
        });
    });
})(jQuery);