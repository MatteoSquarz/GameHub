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
    descrizione varchar(250) NOT NULL,
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
('Base', 'Accesso ad una piccola selezione dei giochi senza premi o ricompense', 20, 1, 'base.jpeg'),
('Deluxe', 'Accesso alla maggior parte dei giochi compresi di ricompense speciali in gioco', 50, 2, 'deluxe.jpeg'),
('Premium', 'Accesso a tutti i giochi del catalogo compresi di tutti i contenuti sbloccabili in gioco', 80, 3, 'premium.jpeg');

INSERT INTO Videogioco (codice, titolo, descrizione, prezzo, dataUscita, pegi, casaSviluppatrice, immagine) VALUES
('00000001', 'Legends of Avalon', 'Un emozionante gioco di avventura in un mondo fantasy', 59.99, '2023-10-15', 12, 'GameStudio A', 'avalon.jpg'),
('00000002', 'Strategic Conquest', 'Un fantastico gioco di strategia militare', 49.99, '2022-05-10', 16, 'StrategyWorks', 'conquest.jpg'),
('00000003', 'Jump Quest', 'Un classico platform reinventato con livelli impegnativi', 39.99, '2021-08-22', 7, 'PlatformMasters', 'jumpquest.jpg'),
('00000004', 'Warzone Alpha', 'Un FPS adrenalinico con modalità multiplayer', 69.99, '2024-01-12', 18, 'ShooterPro', 'warzone.jpg'),
('00000005', 'Eternal Saga', 'Un RPG epico con una storia profonda e ramificata', 79.99, '2020-11-03', 12, 'EpicRPGs', 'eternalsaga.jpg'),
('00000006', 'Racing Thunder', 'Un gioco di corse ad alta velocità', 29.99, '2019-06-20', 7, 'SpeedMasters', 'racingthunder.jpg'),
('00000007', 'Galaxy Explorers', 'Un sandbox spaziale con esplorazione intergalattica', 59.99, '2018-03-15', 10, 'SpaceGames', 'galaxyexplorers.jpg'),
('00000008', 'Mystery Manor', 'Un gioco investigativo con puzzle intricati', 49.99, '2023-09-10', 12, 'PuzzleStudios', 'mysterymanor.jpg'),
('00000009', 'Heroes Clash', 'Un gioco MOBA competitivo', 1.00, '2022-11-05', 12, 'BattleArena Inc.', 'heroesclash.jpg'),
('00000010', 'CyberBattle 2077', 'Un gioco di azione futuristica ambientato in una città distopica', 69.99, '2024-04-08', 18, 'CyberWorlds', 'cyberbattle.jpg');

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