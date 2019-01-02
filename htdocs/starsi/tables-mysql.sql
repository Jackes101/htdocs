CREATE TABLE iwww_emails
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(64),
    subject VARCHAR(64),
    email VARCHAR(64),
    message TEXT,
    created DATETIME
);

INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (1, 'Pepa', 'Žádost', 'pepa@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');
INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (2, 'Marek', 'Žádost', 'marek@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');
INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (3, 'Milos', 'Žádost', 'milos@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');
INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (4, 'Barbora', 'Žádost', 'bara@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');
INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (5, 'Mirek', 'Žádost', 'mirek@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');
INSERT INTO iwww_emails (id, name, subject, email, message, created) VALUES (6, 'Ludmila', 'Žádost', 'ludek@seznam.cz', 'Moje žádost', '2017-11-08 18:08:06');

