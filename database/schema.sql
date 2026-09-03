CREATE TABLE IF NOT EXISTS copie_examen (
    id SERIAL PRIMARY KEY,
    date_depot TIMESTAMP NOT NULL,
    note_brute NUMERIC(4, 2) NOT NULL CHECK (note_brute >= 0 AND note_brute <= 20),
    note_finale NUMERIC(4, 2) NOT NULL CHECK (note_finale >= 0 AND note_finale <= 20),
    penalite_appliquee BOOLEAN NOT NULL DEFAULT FALSE,
    date_limite TIMESTAMP NOT NULL
);

INSERT INTO copie_examen (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
VALUES ('2024-01-15 10:30:00', 15.5, 15.5, FALSE, '2024-01-20 23:59:59');
