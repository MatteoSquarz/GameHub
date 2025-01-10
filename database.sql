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
    titolo varchar(20) NOT NULL,
    descrizione varchar(1000) NOT NULL,
    prezzo decimal NOT NULL,
    dataUscita date NOT NULL,
    pegi int NOT NULL,
    casaSviluppatrice varchar(30) NOT NULL,
    immagine varchar(20) NOT NULL,
    PRIMARY KEY(codice)
);

CREATE TABLE Piattaforma(
    nome varchar(20) NOT NULL,
    annoUscita year NOT NULL,
    casaProduttrice varchar(30) NOT NULL,
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

CREATE TABLE User(
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
    FOREIGN KEY(utente) REFERENCES User(username),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice)
);

CREATE TABLE StoricoAbbonamento(
    utente varchar(20) NOT NULL,
    abbonamento varchar(10) NOT NULL,
    dataInizio date,
    dataFine date,
    PRIMARY KEY(utente, abbonamento),
    FOREIGN KEY(utente) REFERENCES User(username),
    FOREIGN KEY(abbonamento) REFERENCES Abbonamento(nome)
);

CREATE TABLE CategoriaVideogioco(
    categoria varchar(25) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(categoria,videogioco),
    FOREIGN KEY(categoria) REFERENCES Categoria(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice)
);

CREATE TABLE PiattaformaVideogioco(
    piattaforma varchar(20) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(piattaforma,videogioco),
    FOREIGN KEY(piattaforma) REFERENCES Piattaforma(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice)
);

CREATE TABLE AbbonamentoVideogioco(
    abbonamento varchar(10) NOT NULL,
    videogioco varchar(8) NOT NULL,
    PRIMARY KEY(abbonamento,videogioco),
    FOREIGN KEY(abbonamento) REFERENCES Abbonamento(nome),
    FOREIGN KEY(videogioco) REFERENCES Videogioco(codice)
);

/* ------------------------------------------------------------------------------ */
INSERT INTO Utente (username, password) VALUES
('user','user'),
('admin','admin');

INSERT INTO Abbonamento (nome, descrizione, prezzo, livello, immagine) VALUES
('Base', 'Accesso ad una piccola selezione dei giochi senza premi o ricompense.', 20, 1, 'base.jpeg'),
('Deluxe', 'Accesso alla maggior parte dei giochi compresi di ricompense speciali in gioco.', 50, 2, 'deluxe.jpeg'),
('Premium', 'Accesso a tutti i giochi del catalogo compresi di tutti i contenuti sbloccabili in gioco.', 80, 3, 'premium.jpeg');

INSERT INTO Videogioco (codice, titolo, descrizione, prezzo, dataUscita, pegi, casaSviluppatrice, immagine) VALUES
('00000001', 'Legends of Avalon', 'Faery Legends of Avalon ti trasporta in un mondo magico popolato da creature leggendarie. Ambientato in un regno dove la magia e la natura convivono in perfetta armonia, il gioco offre un''esperienza unica di esplorazione e avventura. Assumerai il ruolo di un eroe personalizzabile, con la possibilità di scegliere tra diverse razze mitologiche, ognuna con abilità uniche. La trama si sviluppa attraverso missioni che ti porteranno a scoprire i segreti nascosti del regno di Avalon, a stringere alleanze con altri personaggi e a combattere contro forze oscure che minacciano l''equilibrio del mondo. Grazie a una grafica straordinaria e a una colonna sonora immersiva, Faery Legends of Avalon rappresenta un vero gioiello nel panorama dei giochi di avventura fantasy.', 59.99, '2023-10-15', 12, 'GameStudio A', 'avalon.jpg'),
('00000002', 'Song of Conquest', 'Song of Conquest è un gioco di strategia militare che combina meccaniche di gioco classiche con innovazioni moderne. Ambientato in un mondo medievale ricco di storie e conflitti, il gioco ti mette nei panni di un comandante incaricato di guidare il tuo esercito verso la vittoria. Dovrai gestire risorse, costruire fortificazioni e sviluppare strategie complesse per sconfiggere i tuoi nemici. Ogni battaglia è un mix di tattica e decisioni rapide, con scenari dinamici che cambiano in base alle tue scelte. La modalità campagna offre una narrazione coinvolgente, mentre le modalità multiplayer ti permettono di sfidare amici e giocatori di tutto il mondo. Con una grafica dettagliata e un gameplay profondo, Song of Conquest è un must per gli appassionati di strategia.', 49.99, '2022-05-10', 16, 'StrategyWorks', 'conquest.jpg'),
('00000003', 'Jump King Quest', 'Jump King Quest reinventa il genere dei platform con un approccio unico e sfide impegnative. Il gioco ti mette nei panni di un intrepido avventuriero che deve scalare una torre per raggiungere un obiettivo misterioso. Ogni salto richiede precisione e tempismo, poiché un singolo errore può riportarti ai livelli inferiori. Con un design visivo accattivante e una colonna sonora che si adatta ai tuoi progressi, Jump King Quest offre un''esperienza che combina frustrazione e soddisfazione in egual misura. Il gioco è stato acclamato per la sua difficoltà equilibrata e per la profondità delle sue meccaniche, rendendolo una scelta eccellente per i giocatori che cercano una sfida stimolante.', 39.99, '2021-08-22', 7, 'PlatformMasters', 'jumpquest.jpg'),
('00000004', 'Call of Duty Warzone', 'Call of Duty Warzone è un gioco FPS che offre un''esperienza multiplayer adrenalinica e coinvolgente. Ambientato in un mondo di guerra moderna, il gioco ti permette di scegliere tra diverse modalità, tra cui battle royale e deathmatch a squadre. Con un arsenale vastissimo di armi e attrezzature, ogni partita è una nuova opportunità per dimostrare le tue abilità tattiche e di mira. La grafica realistica e il design audio immersivo contribuiscono a creare un''esperienza di gioco senza pari. Inoltre, il supporto continuo da parte degli sviluppatori garantisce aggiornamenti regolari con nuovi contenuti e miglioramenti, mantenendo il gioco fresco e interessante.', 69.99, '2024-01-12', 18, 'ShooterPro', 'warzone.jpg'),
('00000005', 'Eternal Strands', 'Eternal Strands è un RPG epico che ti immerge in un mondo ricco di storia, magia e mistero. La trama ramificata ti permette di fare scelte che influenzano profondamente lo sviluppo del gioco e il destino dei personaggi che incontri. Con un sistema di combattimento innovativo e una vasta gamma di abilità da sbloccare, Eternal Strands offre un gameplay che premia l''esplorazione e la strategia. Ogni ambientazione è realizzata con una cura meticolosa per i dettagli, rendendo ogni momento del gioco una gioia per gli occhi. La colonna sonora orchestrale e il doppiaggio di alta qualità completano un''esperienza che resterà impressa nella memoria dei giocatori per anni.', 79.99, '2020-11-03', 12, 'EpicRPGs', 'eternalsaga.jpg'),
('00000006', 'Racing Thunder', 'Racing Thunder è un gioco di corse che ti mette al volante di auto ad alta velocità su circuiti spettacolari. Con una vasta gamma di veicoli personalizzabili e modalità di gioco che spaziano dalle corse singole ai tornei, il gioco offre un''esperienza adatta a ogni tipo di giocatore. La fisica realistica e i controlli intuitivi ti permettono di immergerti completamente nell''azione, mentre il multiplayer online aggiunge un livello di competizione che terrà i giocatori incollati allo schermo per ore.', 29.99, '2019-06-20', 7, 'SpeedMasters', 'racingthunder.jpg'),
('00000007', 'Galaxy Explorers', 'Galaxy Explorers è un sandbox spaziale che ti permette di esplorare un universo infinito. Puoi costruire astronavi, colonizzare pianeti e interagire con altre civiltà aliene. La libertà di scelta è il fulcro dell''esperienza, con innumerevoli possibilità per creare la tua avventura personale. La grafica mozzafiato e il design sonoro coinvolgente rendono ogni viaggio nello spazio un''esperienza memorabile.', 59.99, '2018-03-15', 12, 'SpaceGames', 'galaxyexplorers.jpg'),
('00000008', 'Mystery Manor', 'Mystery Manor è un gioco investigativo che ti sfida a risolvere enigmi intricati e a scoprire segreti nascosti. Ambientato in una misteriosa villa piena di sorprese, il gioco combina elementi di avventura e puzzle per offrire un''esperienza unica. Ogni stanza della villa è ricca di dettagli e oggetti interattivi, mentre la narrazione coinvolgente ti tiene con il fiato sospeso fino alla fine.', 49.99, '2023-09-10', 12, 'PuzzleStudios', 'mysterymanor.jpg'),
('00000009', 'Clash of Heroes', 'Clash of Heroes è un MOBA competitivo che mette alla prova le tue abilità strategiche e di coordinazione. Con una varietà di eroi unici, ognuno con abilità speciali, il gioco offre innumerevoli possibilità di personalizzazione e tattiche. La grafica vivace e i comandi intuitivi rendono il gioco accessibile a tutti, mentre la profondità del gameplay lo rende una scelta eccellente per i giocatori più esperti.', 1.00, '2022-11-05', 12, 'BattleArena Inc.', 'heroesclash.jpg'),
('00000010', 'CyberPunk 2077', 'CyberPunk 2077 è un gioco di azione futuristica ambientato in una città distopica. Esplora un mondo aperto ricco di dettagli, con missioni principali e secondarie che ti permettono di plasmare la tua avventura. La possibilità di personalizzare il tuo personaggio e le sue abilità aggiunge un ulteriore livello di profondità al gameplay. La grafica all''avanguardia e la narrazione avvincente rendono CyberPunk 2077 un titolo imperdibile.', 69.99, '2024-04-08', 18, 'CyberWorlds', 'cyberbattle.jpg');

INSERT INTO Piattaforma (nome, annoUscita, casaProduttrice) VALUES
('PC', 1981, 'IBM'),
('PlayStation', 1994, 'Sony'),
('Xbox', 2001, 'Microsoft'),
('Nintendo Switch', 2017, 'Nintendo'),
('Mobile', 2008, 'Various');

INSERT INTO Categoria (nome) VALUES
('Azione'),
('Avventura'),
('Strategia'),
('RPG'),
('FPS'),
('Platform'),
('Puzzle'),
('Corsa'),
('MOBA'),
('Sandbox');

INSERT INTO Admin (username) VALUES
('admin');

INSERT INTO User (username, nome, cognome, dataNascita, email, abbonamentoAttuale, dataInizio, dataFine) VALUES
('user', 'Utente', 'Generico', '2001-10-15', 'utente.generico@gmail.de', 'Base', '2024-12-08', '2025-12-08');

INSERT INTO Vendita (utente, data, totale, videogioco) VALUES
('user', '2024-12-13', 59.99, '00000001'),
('user', '2024-11-09', 69.99, '00000004'),
('user', '2024-11-09', 59.99, '00000007'),
('user', '2024-10-25', 69.99, '00000010');

INSERT INTO StoricoAbbonamento (utente, abbonamento, dataInizio, dataFine) VALUES
('user', 'Premium', '2023-10-21', '2024-10-21');

INSERT INTO CategoriaVideogioco (categoria, videogioco) VALUES
('Avventura', '00000001'),
('Strategia', '00000002'),
('Platform', '00000003'),
('FPS', '00000004'),
('RPG', '00000005'),
('Azione', '00000001'),
('Azione', '00000004'),
('Corsa', '00000006'),
('Sandbox', '00000007'),
('Puzzle', '00000008'),
('MOBA', '00000009'),
('Azione', '00000010');

INSERT INTO PiattaformaVideogioco (piattaforma, videogioco) VALUES
('PC', '00000001'),
('PC', '00000002'),
('PlayStation', '00000003'),
('Xbox', '00000004'),
('Nintendo Switch', '00000005'),
('Mobile', '00000003'),
('PC', '00000005'),
('PlayStation', '00000004'),
('PC', '00000006'),
('Xbox', '00000007'),
('Nintendo Switch', '00000008'),
('PC', '00000009'),
('PlayStation', '00000010');

INSERT INTO AbbonamentoVideogioco (abbonamento, videogioco) VALUES
('Base', '00000001'),
('Base', '00000002'),
('Base', '00000003'),
('Deluxe', '00000001'),
('Deluxe', '00000002'),
('Deluxe', '00000005'),
('Deluxe', '00000006'),
('Deluxe', '00000009'),
('Premium', '00000001'),
('Premium', '00000002'),
('Premium', '00000003'),
('Premium', '00000004'),
('Premium', '00000005'),
('Premium', '00000006'),
('Premium', '00000007'),
('Premium', '00000008'),
('Premium', '00000009'),
('Premium', '00000010');