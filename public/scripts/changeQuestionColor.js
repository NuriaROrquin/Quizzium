$(window).on('load', function () {
    changeBackgroundColor();

    $('#categoria').click(function (event) {
        changeBackgroundColor(event);
    })
});


function changeBackgroundColor(event) {
    let category = event && event.target ? event.target.value : 1;

    let color = colorChoice(category);

    $('#form-container-question').css({'backgroundColor': color});
}

function colorChoice(category) {

    var color;
    switch (category) {
        case '2':
            color = '#BEA821';
            break;
        case '3':
            color = '#DC0000';
            break;
        case '4':
            color = '#0176D2';
            break;
        case '5':
            color = '#FF69B4';
            break;
        case '6':
            color = '#FF9400';
            break;
        default:
            color = '#008639';
            break;
    }

    return color;
}
