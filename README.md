# Dare To Hair

Dit project is gemaakt met Laravel en gebruikt Laravel (Herd) als lokale development omgeving.

## Vereisten

Zorg ervoor dat je het volgende hebt geïnstalleerd:

- PHP
- Composer
- MySQL
- Laravel

## Installatie

### Clone eerst het project:

`git clone <repository-url>`

### Ga daarna naar de projectmap:

` cd <projectnaam>`

### Installeer alle dependencies:

` composer install`

### Maak een .env bestand aan:

`cp .env.example .env`

### Genereer de application key:

`php artisan key:generate`

### Configureer vervolgens je databasegegevens in het .env bestand.

Database opzetten

### Om alles opnieuw correct op te zetten met de nieuwste rollen, seeders en testdata, raden we aan om het volgende commando te gebruiken:

`php artisan migrate:fresh --seed`

Dit verwijdert alle tabellen, maakt ze opnieuw aan en vult de database automatisch met testdata.

## Seeder / Testdata

De projectdatabase bevat standaard seeders en factories voor testdoeleinden.

De volgende data wordt automatisch aangemaakt:

Standaard behandelingen / prijslijst data
medewerkers
klanten
Testaccounts
Standaard admin account

Gebruik een van de volgende accounts om in te loggen:

- Email: admin@test.nl
- Wachtwoord: Password1
  of
- Email: client@test.nl
- wachtwoord: Password1

## Project starten

Wanneer je Laravel Herd gebruikt, zou het project automatisch beschikbaar moeten zijn via een lokale URL.

## Start eventueel de development server handmatig met:

`php artisan serve`

#### Handige commando’s

- Cache legen
  `php artisan optimize:clear`

- Migraties opnieuw uitvoeren met seeders
  `php artisan migrate:fresh --seed`
