(function ($) {
    $('#generator-modelclass').on('blur', function () {
        var modelClass = $(this).val();
        if (modelClass !== '') {
            var fixtureClass = modelClass.split('\\').slice(-1)[0] + 'Fixture';
            $('#generator-fixtureclass').val(fixtureClass);
            var dataFile = modelClass
                .split('\\')
                .slice(-1)[0]
                .replace(/\.?([A-Z])/g, function (x,y){return "_" + y.toLowerCase()})
                .replace(/^_/, "")
                + '.php';
            $('#generator-datafile').val(dataFile);
        }
    });
})(jQuery);
