
    function mostrarPopup() {
    var popupBackground = document.getElementById('popup-background');
    var popup = document.getElementById('popup');
    var disableButton = document.getElementById('disable-button');

    popupBackground.style.display = 'block';
    popup.style.display = 'block';
    disableButton.disabled = true;
}

    function cerrarPopup() {
    var popupBackground = document.getElementById('popup-background');
    var popup = document.getElementById('popup');
    var disableButton = document.getElementById('disable-button');

    popupBackground.style.display = 'none';
    popup.style.display = 'none';
    disableButton.disabled = false;
}

    function mostrarReporte() {
    var popupBackground = document.getElementById('popup-background');
    var popup = document.getElementById('popup');
    var disableButton = document.getElementById('disable-button');

    popupBackground.style.display = 'block';
    popup.style.display = 'block';
    disableButton.disabled = true;
}

    function ocultarReporte() {
    var popupBackground = document.getElementById('popup-background');
    var popup = document.getElementById('popup');
    var disableButton = document.getElementById('disable-button');

    popupBackground.style.display = 'none';
    popup.style.display = 'none';
    disableButton.disabled = false;
}
