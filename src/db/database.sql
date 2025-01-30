/*
   _____                    _                    _______    _          _ _
  / ____|                  (_)                  |__   __|  | |        | | |
 | |     _ __ ___  __ _ _____  ___  _ __   ___     | | __ _| |__   ___| | | ___
 | |    | '__/ _ \/ _` |_  / |/ _ \| '_ \ / _ \    | |/ _` | '_ \ / _ \ | |/ _ \
 | |____| | |  __/ (_| |/ /| | (_) | | | |  __/    | | (_| | |_) |  __/ | |  __/
  \_____|_|  \___|\__,_/___|_|\___/|_| |_|\___|    |_|\__,_|_.__/ \___|_|_|\___|

*/

USE msquarzo;

DROP TABLE IF EXISTS AbbonamentoVideogioco;
DROP TABLE IF EXISTS PiattaformaVideogioco;
DROP TABLE IF EXISTS CategoriaVideogioco;
DROP TABLE IF EXISTS Vendita;
DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Categoria;
DROP TABLE IF EXISTS Piattaforma;
DROP TABLE IF EXISTS Videogioco;
DROP TABLE IF EXISTS Abbonamento;
DROP TABLE IF EXISTS Utente;

CREATE TABLE Utente(
    username varchar(20) NOT NULL,
    password varchar(20) NOT NULL,
    PRIMARY KEY(username)
);

CREATE TABLE Abbonamento(
    nome varchar(10) NOT NULL,
    descrizione varchar(100) NOT NULL,
    prezzo decimal NOT NULL,
    livello int NOT NULL,
    immagine varchar(20) NOT NULL,
    PRIMARY KEY(nome)
);

CREATE TABLE Videogioco(
    codice varchar(8) NOT NULL,
    titolo varchar(50) NOT NULL,
    descrizione varchar(1000) NOT NULL,
    prezzo decimal NOT NULL,
    dataUscita date NOT NULL,
    pegi int NOT NULL,
    casaSviluppatrice varchar(50) NOT NULL,
    immagine varchar(50) NOT NULL,
    PRIMARY KEY(codice)
);

CREATE TABLE Piattaforma(
    nome varchar(40) NOT NULL,
    annoUscita year NOT NULL,
    casaProduttrice varchar(40) NOT NULL,
    PRIMARY KEY(nome)
);

CREATE TABLE Categoria(
    nome varchar(25) NOT NULL,
    PRIMARY KEY(nome)
);

CREATE TABLE Admin(
    username varchar(20) NOT NULL,
    PRIMARY KEY(username),
    FOREIGN KEY(username) REFERENCES Utente(username) 
);

CREATE TABLE Cliente(
    username varchar(20) NOT NULL,
    nome varchar(20) NOT NULL,
    cognome varchar(20) NOT NULL,
    dataNascita date NOT NULL,
    email varchar(50) NOT NULL,
    abbonamentoAttuale varchar(10),
    dataInizio date,
    dataFine date,
    PRIMARY KEY(username),
    FOREIGN KEY(username) REFERENCES Utente(username),
    FOREIGN KEY(abbonamentoAttuale) REFERENCES Abbonamento(nome)
);

CREATE TABLE Vendita(
    utente varchar(20) NOT NULL,
    data date NOT NULL,
    totale decimal NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(utente, data, videogioco),
    FOREIGN KEY(utente) REFERENCES Cliente(username),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice) ON DELETE CASCADE
);

CREATE TABLE CategoriaVideogioco(
    categoria varchar(25) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(categoria,videogioco),
    FOREIGN KEY(categoria) REFERENCES Categoria(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice) ON DELETE CASCADE
);

CREATE TABLE PiattaformaVideogioco(
    piattaforma varchar(40) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(piattaforma,videogioco),
    FOREIGN KEY(piattaforma) REFERENCES Piattaforma(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice) ON DELETE CASCADE
);

CREATE TABLE AbbonamentoVideogioco(
    abbonamento varchar(10) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(abbonamento,videogioco),
    FOREIGN KEY(abbonamento) REFERENCES Abbonamento(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice) ON DELETE CASCADE
);

/*
  _____                  _                            _          _______    _          _ _
 |  __ \                | |                          | |        |__   __|  | |        | | |
 | |__) |__  _ __   ___ | | __ _ _ __ ___   ___ _ __ | |_ ___      | | __ _| |__   ___| | | ___
 |  ___/ _ \| '_ \ / _ \| |/ _` | '_ ` _ \ / _ \ '_ \| __/ _ \     | |/ _` | '_ \ / _ \ | |/ _ \
 | |  | (_) | |_) | (_) | | (_| | | | | | |  __/ | | | || (_) |    | | (_| | |_) |  __/ | |  __/
 |_|   \___/| .__/ \___/|_|\__,_|_| |_| |_|\___|_| |_|\__\___/     |_|\__,_|_.__/ \___|_|_|\___|
            | |
            |_|
*/

INSERT INTO Utente (username, password) VALUES
('user','user'),
('admin','admin');

INSERT INTO Abbonamento (nome, descrizione, prezzo, livello, immagine) VALUES
('Bronzo', 'Accesso ad una piccola selezione dei giochi senza premi o ricompense.', 20, 1, 'base.jpeg'),
('Argento', 'Accesso alla maggior parte dei giochi compresi di ricompense speciali in gioco.', 50, 2, 'deluxe.jpeg'),
('Oro', 'Accesso a tutti i giochi del catalogo compresi di tutti i contenuti sbloccabili in gioco.', 80, 3, 'premium.jpeg');

INSERT INTO Videogioco (codice, titolo, descrizione, prezzo, dataUscita, pegi, casaSviluppatrice, immagine) VALUES
('00000001', '<span lang="en">Legends of Avalon</span>', '<span lang="en">Faery Legends of Avalon</span> ti trasporta in un mondo magico popolato da creature leggendarie. Ambientato in un regno dove la magia e la natura convivono in perfetta armonia, il gioco offre un''esperienza unica di esplorazione e avventura. Assumerai il ruolo di un eroe personalizzabile, con la possibilità di scegliere tra diverse razze mitologiche, ognuna con abilità uniche. La trama si sviluppa attraverso missioni che ti porteranno a scoprire i segreti nascosti del regno di <span lang="en">Avalon</span>, a stringere alleanze con altri personaggi e a combattere contro forze oscure che minacciano l''equilibrio del mondo. Grazie a una grafica straordinaria e a una colonna sonora immersiva, <span lang="en">Faery</span> Legends of Avalon</span> rappresenta un vero gioiello nel panorama dei giochi di avventura <span lang="en">fantasy</span>.', 59, '2023-10-15', 12, '<span lang="en">GameStudio</span> A', 'avalon.jpg'),
('00000002', '<span lang="en">Song of Conquest</span>', '<span lang="en">Song of Conquest</span> è un gioco di strategia militare che combina meccaniche di gioco classiche con innovazioni moderne. Ambientato in un mondo medievale ricco di storie e conflitti, il gioco ti mette nei panni di un comandante incaricato di guidare il tuo esercito verso la vittoria. Dovrai gestire risorse, costruire fortificazioni e sviluppare strategie complesse per sconfiggere i tuoi nemici. Ogni battaglia è un insieme di tattica e decisioni rapide, con scenari dinamici che cambiano in base alle tue scelte. La modalità campagna offre una narrazione coinvolgente, mentre le modalità multigiocatore ti permettono di sfidare amici e giocatori di tutto il mondo. Con una grafica dettagliata e un <span lang="en">gameplay</span> profondo, <span lang="en">Song of Conquest</span> è un <span lang="en">must</span> per gli appassionati di strategia.', 49, '2022-05-10', 16, '<span lang="en">StrategyWorks</span>', 'conquest.jpg'),
('00000003', '<span lang="en">Jump King Quest</span>', '<span lang="en">Jump King Quest</span> reinventa il genere dei <span lang="en">platform</span> con un approccio unico e sfide impegnative. Il gioco ti mette nei panni di un intrepido avventuriero che deve scalare una torre per raggiungere un obiettivo misterioso. Ogni salto richiede precisione e tempismo, poiché un singolo errore può riportarti ai livelli inferiori. Con un impatto visivo accattivante e una colonna sonora che si adatta ai tuoi progressi, <span lang="en">Jump King Quest</span> offre un''esperienza che combina frustrazione e soddisfazione in egual misura. Il gioco è stato acclamato per la sua difficoltà equilibrata e per la profondità delle sue meccaniche, rendendolo una scelta eccellente per i giocatori che cercano una sfida stimolante.', 39, '2021-08-22', 7, '<span lang="en">PlatformMasters</span>', 'jumpquest.jpg'),
('00000004', '<span lang="en">Call of Duty Warzone</span>', '<span lang="en">Call of Duty Warzone</span> è un gioco che offre un''esperienza multigiocatore adrenalinica e coinvolgente. Ambientato in un mondo di guerra moderna, il gioco ti permette di scegliere tra diverse modalità, tra cui tutti contro tutti e <span lang="en">deathmatch</span> a squadre. Con un arsenale vastissimo di armi e attrezzature, ogni partita è una nuova opportunità per dimostrare le tue abilità tattiche e di mira. La grafica realistica e l''audio immersivo contribuiscono a creare un''esperienza di gioco senza pari. Inoltre, il supporto continuo da parte degli sviluppatori garantisce aggiornamenti regolari con nuovi contenuti e miglioramenti, mantenendo il gioco fresco e interessante.', 69, '2024-01-12', 18, '<span lang="en">ShooterPro</span>', 'warzone.jpg'),
('00000005', '<span lang="en">Eternal Strands</span>', '<span lang="en">Eternal Strands</span> è un gioco epico che ti immerge in un mondo ricco di storia, magia e mistero. La trama ramificata ti permette di fare scelte che influenzano profondamente lo sviluppo del gioco e il destino dei personaggi che incontri. Con un sistema di combattimento innovativo e una vasta gamma di abilità da sbloccare, <span lang="en">Eternal Strands</span> offre un <span lang="en">gameplay</span> che premia l''esplorazione e la strategia. Ogni ambientazione è realizzata con una cura meticolosa per i dettagli, rendendo ogni momento del gioco una gioia per gli occhi. La colonna sonora orchestrale e il doppiaggio di alta qualità completano un''esperienza che resterà impressa nella memoria dei giocatori per anni.', 79, '2020-11-03', 12, '<span lang="en">EpicRPGs</span>', 'eternalsaga.jpg'),
('00000006', '<span lang="en">Racing Thunder</span>', '<span lang="en">Racing Thunder</span> è un gioco di corse che ti mette al volante di auto ad alta velocità su circuiti spettacolari. Con una vasta gamma di veicoli personalizzabili e modalità di gioco che spaziano dalle corse singole ai tornei, il gioco offre un''esperienza adatta a ogni tipo di giocatore. La fisica realistica e i controlli intuitivi ti permettono di immergerti completamente nell''azione, mentre il <span lang="en">multiplayer online</span> aggiunge un livello di competizione che terrà i giocatori incollati allo schermo per ore.', 29, '2019-06-20', 7, '<span lang="en">SpeedMasters</span>', 'racingthunder.jpg'),
('00000007', '<span lang="en">Galaxy Explorers</span>', '<span lang="en">Galaxy Explorers</span> è un <span lang="en">sandbox</span> spaziale che ti permette di esplorare un universo infinito. Puoi costruire astronavi, colonizzare pianeti e interagire con altre civiltà aliene. La libertà di scelta è il fulcro dell''esperienza, con innumerevoli possibilità per creare la tua avventura personale. La grafica mozzafiato e il <span lang="en">design</span> sonoro coinvolgente rendono ogni viaggio nello spazio un''esperienza memorabile.', 59, '2018-03-15', 12, '<span lang="en">SpaceGames</span>', 'galaxyexplorers.jpg'),
('00000008', '<span lang="en">Mystery Manor</span>', '<span lang="en">Mystery Manor</span> è un gioco investigativo che ti sfida a risolvere enigmi intricati e a scoprire segreti nascosti. Ambientato in una misteriosa villa piena di sorprese, il gioco combina elementi di avventura e rompicapo per offrire un''esperienza unica. Ogni stanza della villa è ricca di dettagli e oggetti interattivi, mentre la narrazione coinvolgente ti tiene con il fiato sospeso fino alla fine.', 49, '2023-09-10', 12, '<span lang="en">PuzzleStudios</span>', 'mysterymanor.jpg'),
('00000009', '<span lang="en">Clash of Heroes</span>', '<span lang="en">Clash of Heroes</span> è un gioco competitivo che mette alla prova le tue abilità strategiche e di coordinazione. Con una varietà di eroi unici, ognuno con abilità speciali, il gioco offre innumerevoli possibilità di personalizzazione e tattiche. La grafica vivace e i comandi intuitivi rendono il gioco accessibile a tutti, mentre la profondità del gioco lo rende una scelta eccellente per i giocatori più esperti.', 1, '2022-11-05', 12, '<span lang="en">BattleArena Inc</span>', 'heroesclash.jpg'),
('00000010', '<span lang="en">CyberPunk</span> 2077', '<span lang="en">CyberPunk</span> 2077 è un gioco di azione futuristica ambientato in una città distopica. Esplora un mondo aperto ricco di dettagli, con missioni principali e secondarie che ti permettono di plasmare la tua avventura. La possibilità di personalizzare il tuo personaggio e le sue abilità aggiunge un ulteriore livello di profondità al gioco. La grafica all''avanguardia e la narrazione avvincente rendono <span lang="en">CyberPunk</span> 2077 un titolo imperdibile.', 69, '2024-04-08', 18, '<span lang="en">CyberWorlds</span>', 'cyberbattle.jpg');

INSERT INTO Piattaforma (nome, annoUscita, casaProduttrice) VALUES
('<span lang="en">PC</span>', 1981, '<span lang="en">IBM</span>'),
('<span lang="en">PlayStation</span>', 1994, '<span lang="en">Sony</span>'),
('<span lang="en">Xbox</span>', 2001, '<span lang="en">Microsoft</span>'),
('<span lang="en">Nintendo</span>', 2017, '<span lang="en">Nintendo</span>'),
('<span lang="en">Mobile</span>', 2008, '<span lang="en">Various</span>');

INSERT INTO Categoria (nome) VALUES
('Azione'),
('Avventura'),
('Strategia'),
('Rompicapo'),
('Sparatutto'),
('Simulazione'),
('Passatempo'),
('Corsa'),
('Gestionale'),
('Sopravvivenza');

INSERT INTO Admin (username) VALUES
('admin');

INSERT INTO Cliente (username, nome, cognome, dataNascita, email, abbonamentoAttuale, dataInizio, dataFine) VALUES
('user', 'Utente', 'Generico', '2001-10-15', 'utente.generico@gmail.de', 'Bronzo', '2024-12-08', '2025-12-08');

INSERT INTO Vendita (utente, data, totale, videogioco) VALUES
('user', '2024-12-13', 59, '00000001'),
('user', '2024-11-09', 69, '00000004'),
('user', '2024-11-09', 59, '00000007'),
('user', '2024-10-25', 69, '00000010');

INSERT INTO CategoriaVideogioco (categoria, videogioco) VALUES
('Avventura', '00000001'),
('Strategia', '00000002'),
('Simulazione', '00000003'),
('Sparatutto', '00000004'),
('Rompicapo', '00000005'),
('Azione', '00000001'),
('Azione', '00000004'),
('Corsa', '00000006'),
('Sopravvivenza', '00000007'),
('Passatempo', '00000008'),
('Gestionale', '00000009'),
('Azione', '00000010');

INSERT INTO PiattaformaVideogioco (piattaforma, videogioco) VALUES
('<span lang="en">PC</span>', '00000001'),
('<span lang="en">PC</span>', '00000002'),
('<span lang="en">PlayStation</span>', '00000003'),
('<span lang="en">Xbox</span>', '00000004'),
('<span lang="en">Nintendo</span>', '00000005'),
('<span lang="en">Mobile</span>', '00000003'),
('<span lang="en">PC</span>', '00000005'),
('<span lang="en">PlayStation</span>', '00000004'),
('<span lang="en">PC</span>', '00000006'),
('<span lang="en">Xbox</span>', '00000007'),
('<span lang="en">Nintendo</span>', '00000008'),
('<span lang="en">PC</span>', '00000009'),
('<span lang="en">PlayStation</span>', '00000010');

INSERT INTO AbbonamentoVideogioco (abbonamento, videogioco) VALUES
('Bronzo', '00000001'),
('Bronzo', '00000002'),
('Argento', '00000003'),
('Argento', '00000001'),
('Argento', '00000002'),
('Argento', '00000005'),
('Argento', '00000006'),
('Argento', '00000009'),
('Oro', '00000001'),
('Oro', '00000002'),
('Oro', '00000003'),
('Oro', '00000004'),
('Oro', '00000005'),
('Oro', '00000006'),
('Oro', '00000007'),
('Oro', '00000008'),
('Oro', '00000009'),
('Oro', '00000010');