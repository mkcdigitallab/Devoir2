CREATE TABLE IF NOT EXISTS copie_examen (
    id SERIAL PRIMARY KEY,
    date_depot TIMESTAMP NOT NULL,
    note_brute NUMERIC(4, 2) NOT NULL CHECK (note_brute >= 0 AND note_brute <= 20),
    note_finale NUMERIC(4, 2) NOT NULL CHECK (note_finale >= 0 AND note_finale <= 20),
    penalite_appliquee BOOLEAN NOT NULL DEFAULT FALSE,
    date_limite TIMESTAMP NOT NULL
);
