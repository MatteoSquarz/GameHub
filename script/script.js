var dettagli_registrazione = {
    "nome":["Es: Leonardo", /^[A-Za-z\ ]{2,}$/, "Non sono ammessi numeri o caratteri speciali, la lunghezza minima consentita è di almeno 2 caratteri"],
    "cognome":["Es: Rossi", /^[A-Za-z\ \']{2,}$/,"Non sono ammessi numeri o caratteri speciali, la lunghezza minima consentita è di almeno 2 caratteri"],
    "dataNascita":["", /^\d{4}\-\d{2}\-\d{2}$/, "Formato data non corretto"],
    "email":["Es: leo.rossi24@gmail.com", /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,"Non sono ammessi caratteri speciali o lettere maiuscole oppure il formato non corretto"],
    "username":["Es: LeoRossii23", /^[a-zA-Z0-9]{2,}$/,"Sono ammessi solo numeri e lettere, la lunghezza minima consentita è di almeno 2 caratteri"],
    "password":["Caratteri consentiti: tutte le lettere, numeri e i seguenti caratteri speciali !@#%", /^[A-Za-z0-9\!\@\#\%]{8,}$/,"Sono ammessi solo numeri, lettere e i seguenti caratteri speciali !@#%, la lunghezza minima consentita è di 8 caratteri"]
};

var dettagli_inserimento = {
    "codice":["Es: 00000010", /^[0-9]{8,8}$/, "Il codice contiene solo numeri e deve essere di 8 caratteri"],
    "titolo":["Es: La casa degli incubi", /^[A-Za-z0-9\ \']{2,20}$/,"Il titolo non può contenere caratteri speciali, deve contenere minimo 2 caratteri e massimo 20"],
    "data-uscita":["Formato: dd/mm/yyyy", /^\d{4}\-\d{2}\-\d{2}$/, "Formato data non corretto"],
    "pegi":["Età consigliata", , ],
    "prezzo":["Es: 25", /^([0-9]{1,3})$/,"Il prezzo è compreso tra 0 e 999"],
    "casa-sviluppatrice":["Es: Nightmare House", /^[A-Za-z0-9\ \']{2,30}$/,"La casa sviluppatrice contiene solo lettere o numeri, deve contenere minimo 2 caratteri e massimo 30"],
    "descrizione":["", /^[\s\S]{20,1000}$/,"La descrizione deve essere di almeno 20 caratteri e massimo 1000"]
}

var dettagli_rimozione = {
    "codice-rimozione":["Es: 00000010", /^[0-9]{8,8}$/, "Il codice contiene solo numeri e deve essere di 8 caratteri"]
}

var dettagli_modifica = {
    "abbonamento":["Scegli abbonamento", ,""],
    "nuovo-costo":["Es: 25", /^([0-9]{1,3})$/,"Il prezzo è compreso tra 0 e 999"]
}




function caricamento(tipo) {
    var dettagli_form;
    switch (tipo)
    {
        case "registrazione":
            dettagli_form = dettagli_registrazione;
        break;
        case "inserimento":
            dettagli_form = dettagli_inserimento;
        break;
        case "rimozione":
            dettagli_form = dettagli_rimozione;
        break;
        case "modifica":
            dettagli_form = dettagli_modifica;
        break;
    }

    for(var key in dettagli_form){
        var input = document.getElementById(key);
        messaggio(input, 0, tipo);
        input.onblur = function () {validazioneCampo(this, tipo); };
    }
}
    
function validazioneCampo(input, tipo) {
    var dettagli_form;
    switch (tipo)
    {
        case "registrazione":
            dettagli_form = dettagli_registrazione;
        break;
        case "inserimento":
            dettagli_form = dettagli_inserimento;
        break;
        case "rimozione":
            dettagli_form = dettagli_rimozione;
        break;
        case "modifica":
            dettagli_form = dettagli_modifica;
        break;
    }

    var text = input.value;
    var regex = dettagli_form[input.id][1];
    
    //rimuovo messaggio di errore se presente
    var p = input.parentNode;
    if (p.children.length > 2) {
        p.removeChild(p.children[2]);
    }
    if (text.search(regex) != 0) {
        messaggio(input, 1, tipo);
        return false;
    }
    return true;
}
    
function validazioneForm(tipo) {
    var dettagli_form;
    switch (tipo)
    {
        case "registrazione":
            dettagli_form = dettagli_registrazione;
        break;
        case "inserimento":
            dettagli_form = dettagli_inserimento;
        break;
        case "rimozione":
            dettagli_form = dettagli_rimozione;
        break;
        case "modifica":
            dettagli_form = dettagli_modifica;
        break;
    }

    for(var key in dettagli_form){
        var input = document.getElementById(key);
        if(!validazioneCampo(input, tipo)){
            input.focus();
            input.select();
            return false;
        }
    }
    return true;
}
    
function messaggio(input, mode, tipo) {
    /* mode = 0, modalità input
    mode = 1, modalità errore */
    var dettagli_form;
    switch (tipo)
    {
        case "registrazione":
            dettagli_form = dettagli_registrazione;
        break;
        case "inserimento":
            dettagli_form = dettagli_inserimento;
        break;
        case "rimozione":
            dettagli_form = dettagli_rimozione;
        break;
        case "modifica":
            dettagli_form = dettagli_modifica;
        break;
    }

    var node;//tag con messaggio
    var p = input.parentNode;

    if(!mode){
        //creo messaggio di aiuto
        node = document.createElement("span");
        node.className = "hintText";
        node.appendChild(document.createTextNode(dettagli_form[input.id][0]));
    }
    else{
        //creo messaggio di errore
        node = document.createElement("strong");
        node.className = "errorText";
        node.appendChild(document.createTextNode(dettagli_form[input.id][2]));
    }
    p.appendChild(node);
}