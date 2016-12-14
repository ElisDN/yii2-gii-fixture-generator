(function ($) {
    $('#generator-modelclass').on('blur', function () {
        var modelClass = $(this).val();
        if (modelClass !== '') {
            var fixtureClassInput = $('#generator-fixtureclass');
            var fixtureClass = fixtureClassInput.val();
            if (fixtureClass === '') {
                fixtureClass = modelClass.split('\\').slice(-1)[0] + 'Fixture';
                fixtureClassInput.val(fixtureClass);
            }
            var dataFileInput = $('#generator-datafile');
            var dataFile = dataFileInput.val();
            if (dataFile === '') {
                dataFile = modelClass
                    .split('\\')
                    .slice(-1)[0]
                    .replace(/\.?([A-Z])/g, function (x, y) { return '_' + y.toLowerCase()} )
                    .replace(/^_/, '')
                    + '.php';
                dataFileInput.val(dataFile);
            }
        }
    });
})(jQuery);