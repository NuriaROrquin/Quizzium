window.addEventListener('load', function() {
    let tiempo = 19;

    let cronometro = setInterval(function() {
        document.getElementById('cronometro').textContent = tiempo;
        tiempo--;

        if(tiempo < 0){
            clearInterval(cronometro);
            document.getElementById('form-game').submit();
        }
    }, 1000);
});