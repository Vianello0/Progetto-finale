CREATE DATABASE bzzbzz;

use bzzbzz;

CREATE TABLE if not exists Wasper(
    IDWasper INT auto_increment not null,
    Nome VARCHAR(15) NOT NULL,
    Cognome VARCHAR(20) NOT NULL,
    Mail VARCHAR(30) NOT NULL,
    DataNascita DATE NOT NULL,
    PasswordUt VARCHAR(60) NOT NULL,
    PRIMARY KEY(IDWasper)
);


CREATE TABLE if NOT Exists Vespa (
    Modello VARCHAR(20) NOT NULL,
    Cilindrata INT(3) NOT NULL,
    nMarce INT(1) NOT NULL,
    vMax INT(3) NOT NULL,
    Posti INT(1) NOT NULL,
    PRIMARY KEY(Modello)
);


CREATE TABLE if NOT Exists VespeWasper (
    IDWasper INT NOT NULL,
    Modello VARCHAR(20) NOT NULL,
    PRIMARY KEY(IDWasper, Modello),
    FOREIGN KEY(IDWasper) REFERENCES Wasper(IDWasper),
    FOREIGN KEY(Modello) REFERENCES Vespa(Modello) on delete CASCADE
);

CREATE TABLE if NOT Exists Residenza (
    IDWasper INT NOT NULL,
    Via VARCHAR(30) NOT NULL,
    CAP INT(5) NOT NULL,
    Città VARCHAR(40) NOT NULL,
    Provincia VARCHAR(20) NOT NULL,
    Foreign Key (IDWasper) REFERENCES Wasper(IDWasper)
);

CREATE TABLE if NOT Exists PezziRicambio(
    IDRicambio INT auto_increment not null,
    Nome VARCHAR(20) NOT NULL,
    Tipo VARCHAR(20) NOT NULL,
    Modello VARCHAR(20) NOT NULL,
    Descrizione VARCHAR(500) NOT NULL,
    Prezzo DECIMAL(5,2) NOT NULL,
    PRIMARY KEY(IDRicambio)
);

CREATE TABLE if not Exists RicambioVespa(
    IDRicambio INT NOT NULL,
    Modello VARCHAR(20) NOT NULL,
    PRIMARY KEY(IDRicambio, Modello),
    FOREIGN KEY(IDRicambio) REFERENCES PezziRicambio(IDRicambio),
    FOREIGN KEY(Modello) REFERENCES Vespa(Modello)
);

-- Inserimento di molti ricambi generici e specifici
INSERT INTO PezziRicambio (Nome, Tipo, Modello, Descrizione, Prezzo) VALUES
('Carburatore 19/19', 'Motore', 'Universale', 'Carburatore Dell''Orto 19/19 adatto a elaborazioni.', 85.50),
('Cuffia Cilindro', 'Motore', 'Vespa 50', 'Cuffia di raffreddamento in plastica per cilindro.', 15.00),
('Marmitta Padellino', 'Scarico', 'Universale', 'Marmitta originale a padellino omologata.', 65.00),
('Sella Lunga', 'Carrozzeria', 'Universale', 'Sella lunga per viaggiare in due comodamente.', 110.00),
('Ammortizzatore Ant.', 'Sospensioni', 'Universale', 'Ammortizzatore anteriore a molla regolabile.', 45.00),
('Faro Anteriore', 'Impianto Elettrico', 'Vespa 50 Special', 'Faro anteriore completo di parabola.', 25.00),
('Dischi Frizione', 'Trasmissione', 'Universale', 'Kit 4 dischi frizione in sughero.', 22.50),
('Crocera Cambio', 'Trasmissione', 'Universale', 'Crocera del cambio rinforzata a 4 marce.', 40.00),
('Albero Motore Anticipato', 'Motore', 'Vespa 125', 'Albero motore con anticipo modificato per prestazioni elevate.', 120.00),
('Gruppo Termico 75cc', 'Motore', 'Vespa 50', 'Kit cilindro e pistone maggiorato 75cc in ghisa.', 95.00),
('Gomma Michelin S83', 'Ruote', 'Universale', 'Pneumatico classico 3.50-10 per ottima tenuta di strada.', 35.00),
('Cerchio Lega 10"', 'Ruote', 'Universale', 'Cerchio tubeless in lega leggera.', 45.00),
('Leva Freno/Frizione', 'Manubrio', 'Universale', 'Coppia di leve in alluminio lucidato.', 18.00),
('Scudo Anteriore', 'Carrozzeria', 'Vespa PX 150', 'Scudo frontale di ricambio da verniciare.', 150.00),
('Rubinetto Benzina', 'Alimentazione', 'Universale', 'Rubinetto con decantatore per serbatoio.', 12.00),
('Statore Elettronico', 'Impianto Elettrico', 'Vespa PX 150', 'Statore 12V a 5 fili.', 60.00),
('Clacson 12V', 'Impianto Elettrico', 'Universale', 'Clacson cromato alimentazione 12V in corrente alternata.', 18.50),
('Specchietto Cromo', 'Accessori', 'Universale', 'Specchietto rotondo cromato con attacco al manubrio.', 22.00),
('Portapacchi Post.', 'Accessori', 'Universale', 'Portapacchi pieghevole cromato.', 65.00),
('Marmitta Espansione', 'Scarico', 'Vespa 125 ET3', 'Scarico ad espansione per massime prestazioni.', 140.00);

-- Associazione dei ricambi ai vari modelli
INSERT INTO RicambioVespa (IDRicambio, Modello) VALUES
(1, 'Vespa 50 Special'), (1, 'Vespa 125 ET3'), (1, 'Vespa PK 50'),
(2, 'Vespa 50'), (2, 'Vespa 50 Special'), (2, 'Vespa PK 50'),
(3, 'Vespa PX 150'), (3, 'Vespa 125'), (3, 'Vespa 150 Sprint'),
(4, 'Vespa 50 Special'), (4, 'Vespa 125 ET3'), (4, 'Vespa Primavera 125'),
(5, 'Vespa 50'), (5, 'Vespa 50 Special'), (5, 'Vespa 125 ET3'),
(6, 'Vespa 50 Special'), 
(7, 'Vespa 50 Special'), (7, 'Vespa 125 ET3'), (7, 'Vespa Primavera 125'), (7, 'Vespa PX 150'),
(8, 'Vespa 50 Special'), (8, 'Vespa 125 ET3'), (8, 'Vespa PX 150'),
(9, 'Vespa 125 ET3'), (9, 'Vespa Primavera 125'),
(10, 'Vespa 50'), (10, 'Vespa 50 Special'), (10, 'Vespa PK 50'),
(11, 'Vespa PX 150'), (11, 'Vespa 150 Sprint'), (11, 'Vespa 200 Rally'), (11, 'Vespa GTS 300'),
(12, 'Vespa PX 150'), (12, 'Vespa 150 Sprint'), (12, 'Vespa 200 Rally'),
(13, 'Vespa 50 Special'), (13, 'Vespa 125 ET3'), (13, 'Vespa PX 150'), (13, 'Vespa Primavera 125'),
(14, 'Vespa PX 150'),
(15, 'Vespa 50 Special'), (15, 'Vespa 125 ET3'), (15, 'Vespa PX 150'), (15, 'Vespa 150 Sprint'), (15, 'Vespa 200 Rally'),
(16, 'Vespa PX 150'), (16, 'Vespa 200 Rally'),
(17, 'Vespa PX 150'), (17, 'Vespa 125 ET3'), (17, 'Vespa Primavera 125'),
(18, 'Vespa 50 Special'), (18, 'Vespa 125 ET3'), (18, 'Vespa PX 150'), (18, 'Vespa 150 Sprint'), (18, 'Vespa Primavera 125'),
(19, 'Vespa PX 150'), (19, 'Vespa 125 ET3'), (19, 'Vespa 150 Sprint'),
(20, 'Vespa 125 ET3'), (20, 'Vespa Primavera 125');

CREATE Table Gadget if NOT Exists(
    IDGadget INT auto increment not null,
    Nome VARCHAR(20) NOT NULL,
    Categoria VARCHAR(20) NOT NULL,
    Taglia VARCHAR(10),
    Descrizione VARCHAR(50) NOT NULL,
    Prezzo DECIMAL(5,2) NOT NULL,
    PRIMARY KEY(IDGadget)
);

/*CREATE TABLE acquisti if NOT Exists(
    IDAcquisto INT auto increment not null,
    IDWasper INT NOT NULL,
    IDGadget INT,
    IDRicambio INT,
    DataAcquisto DATE NOT NULL,
    PRIMARY KEY(IDAcquisto),
    FOREIGN KEY(IDWasper) REFERENCES Wasper(IDWasper),
    FOREIGN KEY(IDGadget) REFERENCES Gadget(IDGadget),
    FOREIGN KEY(IDRicambio) REFERENCES PezziRicambio(IDRicambio)
);
*/
CREATE Table ModuloIScrizione(
    NomeRaduno VARCHAR(20) NOT NULL,
    IDWasper INT NOT NULL,
    Descrizione VARCHAR(50) NOT NULL,
    ModelloVespa VARCHAR(20) NOT NULL,
    Privacy BOOLEAN NOT NULL,
    PRIMARY KEY(NomeRaduno),
    FOREIGN KEY(IDWasper) REFERENCES Wasper(IDWasper)
);

CREATE TABLE RaduniPassati if NOT Exists(
    NomeRaduno VARCHAR(20) NOT NULL,
    DataRaduno DATE NOT NULL,
    Luogo VARCHAR(30) NOT NULL,
    Descrizione VARCHAR(50) NOT NULL,
    PRIMARY KEY(NomeRaduno)
);

CREATE TABLE FotoRaduni if NOT Exists(
    IDFoto INT auto increment not null,
    NomeRaduno VARCHAR(20) NOT NULL,
    Foto VARCHAR(100) NOT NULL,
    PRIMARY KEY(IDFoto),
    FOREIGN KEY(NomeRaduno) REFERENCES RaduniPassati(NomeRaduno)
);



#query

#Visualizzare i dati dei wasper
SELECT Wasper.IDWasper, Nome, Cognome, Mail, DataNascita, Via, Provincia, Regione FROM Wasper
INNER JOIN Residenza USING(IDWasper);

#visualizzare vespe dei wasper
SELECT Nome, Cognome, Modello FROM Wasper
INNER JOIN VespeWasper USING(IDWasper)
INNER JOIN Vespa USING(Modello);

#visualizzare modelli di vespe maggiormente posseduti
SELECT Modello, COUNT(*) AS NumeroPossessori FROM VespeWasper
GROUP BY Modello;

#visualizzare guadagni mensili
SELECT MONTH(DataAcquisto) as Mese, SUM(prezzo) as Ricavi FROM Acquisti
inner join ricambio USING(idRicambio)
inner join gadget USING(idGadget)
group by Mese;

#visualizzare ricambi più acquistati (quindi che si rompono più facilmente)
SELECT pezziRicambio.Nome, COUNT(IdRicambio) AS totaleAcquistati FROM Acquisti
INNER JOIN pezziRicambio USING(IDRicambio)
GROUP BY IDRicambio
ORDER BY totaleAcquistati DESC;

#visualizzare gadget più acquistati
SELECT gadget.Nome, COUNT(IdGadget) AS totaleAcquistati FROM Acquisti
INNER JOIN gadget USING(IDGadget)
GROUP BY IDGadget
ORDER BY totaleAcquistati DESC;

#residenza wasper
CREATE Procedure ResidenzaWasper(OUT INT IDWasper)
SELECT