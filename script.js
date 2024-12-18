var dettagli_form = {
    "nome":["Ex: Gigi", /^[A-Za-z\ ]{2,}$/, "Non sono ammessi numeri o caratteri speciali, il numero di caratteri dev'essere almeno 2"],
    "cognome":["Ex: Rossi", /^[A-Za-z\ \']{2,}$/,"Non sono ammessi numeri o caratteri speciali, il numero di caratteri dev'essere almeno 2"],
    "dataNascita":["", /^\d{4}\-\d{2}\-\d{2}$/, "Formato data non corretto"],
    "email":["Ex: gigi.rossi24@gmail.de", /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,"Non sono ammessi caratteri speciali o lettere maiuscole oppure il formato non corretto"],
    "username":["Ex: GigiRossii23", /^[a-zA-Z0-9]{2,}$/,"Sono ammessi solo numeri e lettere, il numero di caratteri dev'essere almeno 2"],
    "password":["Caratteri consentiti: tutte le lettere, numeri e !@#%", /^[A-Za-z0-9\!\@\#\%]{8,}$/,"Sono ammessi solo numeri, lettere e e !@#%, il numero di caratteri dev'essere almeno 8"]
};


function caricamento() {
    for(var key in dettagli_form){
        var input = document.getElementById(key);
        messaggio(input,0);
        input.onblur = function () {validazioneCampo(this); };
    }
}
    
function validazioneCampo(input) {		
    var text = input.value;
    var regex = dettagli_form[input.id][1];
    
    //rimuovo messaggio di errore se presente
    var p = input.parentNode;
    if (p.children.length > 2) {
        p.removeChild(p.children[2]);
    }
    if (text.search(regex) != 0) {
        messaggio(input, 1);
        input.focus();
        input.select(); //opzionale
        return false;
    }
    return true;
}
    
function validazioneForm() {
    for(var key in dettagli_form){
        var input = document.getElementById(key);
        if(!validazioneCampo(input))
            return false;
    }
    return true;
}
    
function messaggio(input, mode) {
    /* mode = 0, modalità input
    mode = 1, modalità errore */

    var node;//tag con messaggio
    var p = input.parentNode;

    if(!mode){
        //creo messaggio di aiuto
        node = document.createElement("span");
        node.className = "subText";
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
    }
    else{
        //creo messaggio di errore
        node = document.createElement("strong");
        node.className = "subText";
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    }
    p.appendChild(node);
}