function formatarValor() {
    var valor = document.getElementById('txt-valor').value;
    valor = valor.replace(/[^0-9]/g, "");
    if (valor.length > 2) {
        valor = valor.slice(0, valor.length - 2) + "," + valor.slice(valor.length - 2);
    }
    valor = "R$ " + valor;
    document.getElementById('txt-valor').value = valor;
}