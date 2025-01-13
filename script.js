var dettagli_registrazione = {
    "nome":["Ex: Gigi", /^[A-Za-z\ ]{2,}$/, "Non sono ammessi numeri o caratteri speciali, il numero di caratteri dev'essere almeno 2"],
    "cognome":["Ex: Rossi", /^[A-Za-z\ \']{2,}$/,"Non sono ammessi numeri o caratteri speciali, il numero di caratteri dev'essere almeno 2"],
    "dataNascita":["", /^\d{4}\-\d{2}\-\d{2}$/, "Formato data non corretto"],
    "email":["Ex: gigi.rossi24@gmail.de", /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,"Non sono ammessi caratteri speciali o lettere maiuscole oppure il formato non corretto"],
    "username":["Ex: GigiRossii23", /^[a-zA-Z0-9]{2,}$/,"Sono ammessi solo numeri e lettere, il numero di caratteri dev'essere almeno 2"],
    "password":["Caratteri consentiti: tutte le lettere, numeri e !@#%", /^[A-Za-z0-9\!\@\#\%]{8,}$/,"Sono ammessi solo numeri, lettere e e !@#%, il numero di caratteri dev'essere almeno 8"]
};

var dettagli_inserimento = {
    "codice":["Ex: 00000010", /^[0-9]{8,8}$/, "Il codice contiene solo numeri e deve essere di 8 caratteri"],
    "titolo":["Ex: La casa degli incubi", /^[A-Za-z0-9\ \']{2,20}$/,"Il titolo non può contenere caratteri speciali, almeno 2 caratteri max 20"],
    "data-uscita":["Formato: dd/mm/yyyy", /^\d{4}\-\d{2}\-\d{2}$/, "Formato data non corretto"],
    "pegi":["Età consigliata", , ],
    "prezzo":["Ex: 25", /^([0-9]{1,3})$/,"Il prezzo è compreso tra 0 e 999"],
    "casa-sviluppatrice":["Ex: Nightmare House", /^[A-Za-z0-9\ \']{2,30}$/,"La casa sviluppatrice contiene solo lettere o numeri, almeno 2 caratteri max 30"],
    "descrizione":["", /^[\s\S]{20,1000}$/,"La descrizione deve essere di almeno 20 caratteri max 1000"]
}

var dettagli_rimozione = {
    "codice-rimozione":["Ex: 00000010", /^[0-9]{8,8}$/, "Il codice contiene solo numeri e deve essere di 8 caratteri"]
}

var dettagli_modifica = {
    "abbonamento":["In cui si modifica", ,""],
    "nuovo-costo":["Ex: 25", /^([0-9]{1,3})$/,"Il prezzo è compreso tra 0 e 999"]
}




function caricamento(tipo) {
    var dettagli_form;
    switch (tipo)
    {
        case "registrazione":
            dettagli_form = dettagli_registrazione;
        break;
        case "admin":
            dettagli_form = Object.assign(dettagli_inserimento, dettagli_modifica, dettagli_rimozione);
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
        case "admin":
            dettagli_form = Object.assign(dettagli_inserimento, dettagli_modifica, dettagli_rimozione);
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
        input.focus();
        input.select();
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
        case "admin":
            dettagli_form = Object.assign(dettagli_inserimento, dettagli_modifica, dettagli_rimozione);
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