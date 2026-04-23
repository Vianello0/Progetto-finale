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

INSERT INTO Vespa(Modello, Cilindrata, nMarce, vMax, Posti) VALUES("Vespa 50", 50, 3, 40, 1);

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

CREATE TABLE VespeWasper if NOT Exists(
    IDWasper INT NOT NULL,
    Modello VARCHAR(20) NOT NULL,
    PRIMARY KEY(IDWasper, Modello),
    FOREIGN KEY(IDWasper) REFERENCES Wasper(IDWasper),
    FOREIGN KEY(Modello) REFERENCES Vespa(Modello)
);

CREATE TABLE PezziRicambio if NOT Exists(
    IDRicambio INT auto increment not null,
    Nome VARCHAR(20) NOT NULL,
    Tipo VARCHAR(20) NOT NULL,
    Modello VARCHAR(20) NOT NULL,
    Descrizione VARCHAR(50) NOT NULL,
    Prezzo DECIMAL(5,2) NOT NULL,
    PRIMARY KEY(IDRicambio),
    FOREIGN KEY(Modello) REFERENCES Vespa(Modello)
);

CREATE Table Gadget if NOT Exists(
    IDGadget INT auto increment not null,
    Nome VARCHAR(20) NOT NULL,
    Categoria VARCHAR(20) NOT NULL,
    Taglia VARCHAR(10),
    Descrizione VARCHAR(50) NOT NULL,
    Prezzo DECIMAL(5,2) NOT NULL,
    PRIMARY KEY(IDGadget)
);

CREATE TABLE acquisti if NOT Exists(
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