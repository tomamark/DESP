-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 15 Lip 2018, 13:36
-- Wersja serwera: 5.6.39
-- Wersja PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `pt17356_desp`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dni`
--

CREATE TABLE `dni` (
  `id_dnia` int(3) NOT NULL,
  `dzien` varchar(16) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Lista słownikowa dni';

--
-- Zrzut danych tabeli `dni`
--

INSERT INTO `dni` (`id_dnia`, `dzien`) VALUES
(4, 'czwartek'),
(3, 'Ĺroda'),
(5, 'piÄtek'),
(1, 'poniedziaĹek'),
(2, 'wtorek');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `godziny`
--

CREATE TABLE `godziny` (
  `id_godziny` int(4) NOT NULL,
  `czas` varchar(12) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `godziny`
--

INSERT INTO `godziny` (`id_godziny`, `czas`) VALUES
(1, '8:00-8:45'),
(2, '8:50-9:35'),
(3, '9:40-10:25'),
(4, '10:45-11:30'),
(5, '11:35-12:20'),
(6, '12:40-13:25'),
(7, '13:30-14:15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie_ocen`
--

CREATE TABLE `kategorie_ocen` (
  `id_kategorii` int(5) NOT NULL,
  `kategoria` varchar(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kategorie_ocen`
--

INSERT INTO `kategorie_ocen` (`id_kategorii`, `kategoria`) VALUES
(1, 'Sprawdzian'),
(2, 'Zadanie domowe'),
(3, 'AktywnoĹÄ'),
(4, 'Praca na lekcji'),
(5, 'OdpowiedĹş'),
(6, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id_klasy` int(11) NOT NULL,
  `klasa` varchar(4) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `klasy`
--

INSERT INTO `klasy` (`id_klasy`, `klasa`) VALUES
(1, '1A'),
(2, '2A'),
(3, '3A'),
(4, '4A'),
(5, '5A'),
(6, '6A'),
(7, '7A'),
(8, '8A'),
(15, '8B'),
(24, '8C'),
(21, '8D');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontakty`
--

CREATE TABLE `kontakty` (
  `id_uzytkownika` int(11) NOT NULL,
  `id_kontaktu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nieobecnosci`
--

CREATE TABLE `nieobecnosci` (
  `id_nieobecnosci` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `id_rodzica` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `usprawiedliwiona` tinyint(1) NOT NULL,
  `usprawiedliwienie` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `nieobecnosci`
--

INSERT INTO `nieobecnosci` (`id_nieobecnosci`, `id_ucznia`, `id_rodzica`, `data`, `usprawiedliwiona`, `usprawiedliwienie`) VALUES
(2, 6, 2, '2018-05-07', 1, 'Bo tak'),
(3, 3, 2, '2018-06-11', 1, 'Nieobecna z powodu choroby'),
(4, 6, 2, '2018-04-18', 1, 'Nieobecny z powodu wizyty u lekarza'),
(6, 3, 2, '2018-06-13', 1, 'Test uspraw'),
(7, 7, NULL, '2018-06-21', 0, ''),
(8, 12, NULL, '2018-06-21', 0, ''),
(9, 28, NULL, '2018-06-21', 0, ''),
(10, 13, NULL, '2018-06-21', 0, ''),
(11, 6, 2, '2018-06-21', 1, 'Trt'),
(12, 18, NULL, '2018-06-29', 0, ''),
(13, 28, NULL, '2018-06-29', 0, ''),
(14, 3, 2, '2018-06-29', 1, 'Test usprawiedliwienia'),
(15, 3, NULL, '2018-07-14', 0, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `symbol` varchar(2) COLLATE utf8_polish_ci NOT NULL,
  `wartosc` decimal(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `oceny`
--

INSERT INTO `oceny` (`symbol`, `wartosc`) VALUES
('1', '1.00'),
('1+', '1.50'),
('2', '2.00'),
('2-', '1.75'),
('2+', '2.50'),
('3', '3.00'),
('3-', '2.75'),
('3+', '3.50'),
('4', '4.00'),
('4-', '3.75'),
('4+', '4.50'),
('5', '5.00'),
('5-', '4.75'),
('5+', '5.50'),
('6', '6.00'),
('6-', '5.75');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny_ucznia`
--

CREATE TABLE `oceny_ucznia` (
  `id_oceny` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `id_przedmiotuklasy` int(11) NOT NULL,
  `symbol` varchar(2) COLLATE utf8_polish_ci NOT NULL,
  `data` date NOT NULL,
  `id_kategorii` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `oceny_ucznia`
--

INSERT INTO `oceny_ucznia` (`id_oceny`, `id_ucznia`, `id_przedmiotuklasy`, `symbol`, `data`, `id_kategorii`) VALUES
(1, 3, 1, '5', '2018-06-06', 4),
(3, 3, 1, '6', '2018-06-04', 2),
(5, 3, 1, '4+', '2018-06-04', 1),
(6, 3, 2, '4', '2018-06-06', 3),
(7, 3, 2, '3+', '2018-06-08', 4),
(8, 3, 2, '5', '2018-06-09', 6),
(9, 3, 2, '4-', '2018-06-10', 1),
(11, 7, 2, '3', '2018-06-08', 6),
(13, 6, 18, '4', '2018-06-04', 5),
(14, 6, 18, '5', '2018-06-06', 4),
(15, 6, 18, '4-', '2018-06-02', 2),
(16, 6, 19, '3+', '2018-05-16', 5),
(17, 6, 19, '2-', '2018-05-10', 1),
(18, 6, 19, '5', '2018-05-18', 5),
(19, 6, 19, '5', '2018-05-24', 6),
(20, 6, 20, '4', '2018-06-12', 1),
(21, 6, 20, '3+', '2018-06-11', 2),
(23, 6, 19, '6', '2018-06-19', 5),
(24, 6, 19, '1', '2018-06-19', 2),
(25, 6, 19, '4', '2018-06-20', 2),
(27, 3, 16, '5', '2018-06-20', 4),
(28, 7, 16, '5-', '2018-06-20', 4),
(29, 12, 16, '4+', '2018-06-20', 3),
(31, 27, 16, '4+', '2018-06-20', 1),
(32, 3, 16, '3+', '2018-06-20', 1),
(35, 18, 16, '5+', '2018-06-20', 2),
(36, 3, 16, '5', '2018-06-20', 1),
(37, 27, 1, '3-', '2018-06-22', 1),
(38, 18, 1, '3+', '2018-06-22', 1),
(39, 7, 1, '4-', '2018-06-25', 4),
(40, 7, 1, '5', '2018-06-25', 1),
(42, 18, 16, '2-', '2018-07-14', 1),
(43, 13, 16, '3', '2018-07-14', 4),
(44, 41, 16, '3+', '2018-07-14', 2),
(45, 28, 16, '6', '2018-07-14', 3),
(46, 3, 1, '4+', '2018-07-14', 1),
(48, 3, 1, '4-', '2018-07-14', 1),
(50, 12, 1, '5+', '2018-07-15', 3),
(51, 13, 1, '4', '2018-07-15', 1),
(52, 38, 19, '5+', '2018-07-15', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plany_lekcji`
--

CREATE TABLE `plany_lekcji` (
  `id_dnia` int(3) NOT NULL,
  `id_godziny` int(4) NOT NULL,
  `id_przedmiotuklasy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `plany_lekcji`
--

INSERT INTO `plany_lekcji` (`id_dnia`, `id_godziny`, `id_przedmiotuklasy`) VALUES
(1, 2, 16),
(1, 3, 2),
(1, 4, 1),
(2, 1, 1),
(2, 2, 1),
(1, 1, 17),
(1, 2, 18),
(1, 3, 20),
(1, 1, 13),
(2, 6, 10),
(5, 1, 22),
(3, 1, 14),
(3, 2, 10),
(3, 1, 2),
(4, 1, 1),
(1, 1, 29),
(2, 5, 30);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id_przedmiotu` int(5) NOT NULL,
  `przedmiot` varchar(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `przedmioty`
--

INSERT INTO `przedmioty` (`id_przedmiotu`, `przedmiot`) VALUES
(0, 'Godzina wychowawcza'),
(1, 'Edukacja wczesnoszkolna'),
(2, 'Wychowanie fizyczne'),
(3, 'Plastyka'),
(4, 'Technika'),
(5, 'Muzyka'),
(6, 'Informatyka'),
(7, 'Religia'),
(8, 'J. polski'),
(9, 'J. angielski'),
(10, 'Matematyka'),
(11, 'Historia'),
(12, 'Fizyka'),
(13, 'Chemia'),
(14, 'Biologia');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty_klasy`
--

CREATE TABLE `przedmioty_klasy` (
  `id_przedmiotuklasy` int(11) NOT NULL,
  `id_klasy` int(11) NOT NULL,
  `id_przedmiotu` int(11) NOT NULL,
  `id_nauczyciela` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `przedmioty_klasy`
--

INSERT INTO `przedmioty_klasy` (`id_przedmiotuklasy`, `id_klasy`, `id_przedmiotu`, `id_nauczyciela`) VALUES
(1, 1, 1, 5),
(2, 1, 2, 22),
(10, 15, 0, 22),
(11, 15, 2, 22),
(12, 15, 6, 35),
(13, 15, 12, 34),
(14, 15, 13, 36),
(16, 1, 3, 5),
(17, 4, 0, 34),
(18, 4, 4, 33),
(19, 4, 8, 5),
(20, 4, 10, 21),
(21, 2, 0, 32),
(22, 1, 5, 35),
(23, 15, 8, 5),
(25, 8, 13, 36),
(26, 5, 2, 22),
(27, 1, 6, 45),
(29, 8, 9, 33),
(30, 24, 8, 42),
(31, 3, 2, 22);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rodzice_uczniowie`
--

CREATE TABLE `rodzice_uczniowie` (
  `id_rodzica` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `rodzice_uczniowie`
--

INSERT INTO `rodzice_uczniowie` (`id_rodzica`, `id_ucznia`) VALUES
(2, 3),
(20, 18),
(29, 28),
(2, 6),
(26, 13),
(26, 7),
(37, 3),
(37, 6),
(39, 38),
(40, 28),
(55, 12),
(56, 12),
(25, 60);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sprawdziany`
--

CREATE TABLE `sprawdziany` (
  `id_sprawdzianu` int(11) NOT NULL,
  `id_przedmiotuklasy` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `sprawdziany`
--

INSERT INTO `sprawdziany` (`id_sprawdzianu`, `id_przedmiotuklasy`, `data`) VALUES
(3, 1, '2018-06-24'),
(7, 19, '2018-06-28'),
(8, 16, '2018-06-21'),
(10, 1, '2018-06-22'),
(11, 1, '2018-06-21'),
(13, 19, '2018-06-29'),
(14, 1, '2018-06-27'),
(15, 1, '2018-07-19'),
(16, 23, '2018-07-14'),
(17, 23, '2018-07-20'),
(19, 1, '2018-08-15'),
(20, 23, '2018-07-17');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uwagi`
--

CREATE TABLE `uwagi` (
  `id_uwagi` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `id_nauczyciela` int(11) NOT NULL,
  `opis` text COLLATE utf8_polish_ci NOT NULL,
  `negatywna` tinyint(1) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uwagi`
--

INSERT INTO `uwagi` (`id_uwagi`, `id_ucznia`, `id_nauczyciela`, `opis`, `negatywna`, `data`) VALUES
(1, 3, 5, 'Pomagala przy organizacji przedstawienia klasowego', 0, '2018-06-03'),
(2, 6, 33, 'Przeszkadza w prowadzeniu lekcji. Komentuje wypowiedzi nauczyciela.', 1, '2018-06-03'),
(3, 6, 21, 'Napisal program komputerowy pomagajacy uczniom w zrozumieniu zagadnien omawianych na lekcji. Dziekuje!!!', 0, '2018-05-07'),
(4, 3, 5, 'Jak zwykle swietnie przygotowana do zajec. Oby tak dalej. Gratulacje!!!', 0, '2018-06-11'),
(7, 28, 5, 'test uwagi negatywnej', 1, '2018-06-24'),
(8, 6, 5, 'test pozytywnej', 0, '2018-06-24'),
(9, 12, 5, 'Uwaga jak uwaga', 0, '2018-06-25'),
(10, 12, 5, 'Negatywna uwaga', 1, '2018-06-25'),
(11, 6, 5, 'Test negatywne j', 1, '2018-06-25'),
(13, 3, 5, 'Test negatywnej', 1, '2018-07-14'),
(14, 3, 5, 'Test pozytywnej', 0, '2018-07-14'),
(17, 12, 5, 'Test pozytywnej', 0, '2018-07-15'),
(18, 38, 5, 'Test uwagi', 0, '2018-07-15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id_uzytkownika` int(11) NOT NULL,
  `typ` int(3) NOT NULL,
  `imie` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(30) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `telefon` varchar(14) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `id_klasy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id_uzytkownika`, `typ`, `imie`, `nazwisko`, `telefon`, `email`, `haslo`, `id_klasy`) VALUES
(2, 3, 'Agnieszka', 'Adamiak', '', 'aad@wp.pl', 'aaaaaa', NULL),
(3, 2, 'Maja', 'Adamiak', '', 'ma@sp.pl', 'aaaaaa', 1),
(4, 0, 'Adam', 'Administratorski', NULL, 'admin@sp.pl', 'admin00', NULL),
(5, 1, 'Anna', 'Polonistka', NULL, 'anna_p@sp.pl', 'polon00', NULL),
(6, 2, 'Jan', 'Adamiak', NULL, 'ja@sp.pl', 'jadamiak00', 4),
(7, 2, 'Julian', 'Bartkiewicz', '', 'jb@sp.pl', 'aaaaaa', 1),
(12, 2, 'Zofia', 'Czernicka', '', 'zczernicka@sp.pl', 'aaaaaa', 1),
(13, 2, 'Anna', 'Kolon', '', 'akolon@sp.pl', 'aaaaaa', 1),
(15, 2, 'Marta', 'Kolon', '', 'mkolon@sp.pl', 'aaaaaa', 2),
(18, 2, 'Marta', 'JaĹska', '123445', 'mjanska@sp.pl', 'aaaaaa', 1),
(20, 3, 'Alicja', 'JaĹska', '2112112', 'aljan@wp.pl', 'aaaaaa', NULL),
(21, 1, 'Stefan', 'Matematyk', '121', 'smat@sp.pl', 'aaaaaa', NULL),
(22, 1, 'Adrian', 'Wuefista', '12321', 'awf@sp.pl', 'aaaaaa', NULL),
(23, 0, 'Jan', 'Znawca', '', 'janz@sp.pl', 'admin01', NULL),
(25, 3, 'Jan', 'Kowalski', '', 'jkowal@wp.pl', 'aaaaaa', NULL),
(26, 3, 'Jerzy', 'Kolon', '666777999', 'jkolon@wp.pl', 'aaaaaa', NULL),
(27, 2, 'PaweĹ', 'Filipiak', '', 'pfilip@sp.pl', 'aaaaaa', 1),
(28, 2, 'Ksawery', 'Rutkowski', '', 'krut@sp.pl', 'aaaaaaa', 1),
(29, 3, 'Adam', 'Rutkowski', '', 'arut@wp.pl', 'aaaaaa', NULL),
(32, 1, 'Jan', 'Krasicki', '', 'jkras@n.pl', 'aaaaaa', NULL),
(33, 1, 'Roman', 'WaligĂłra', '', 'rwal@n.pl', 'aaaaaa', NULL),
(34, 1, 'Stefan', 'Fizyczny', '', 'sfiz@n.pl', 'aaaaaa', NULL),
(35, 1, 'Kamil', 'Komenda', '', 'lkom@np.pl', 'aaaaaa', NULL),
(36, 1, 'Ali', 'Chemiczny', '', 'achem@n.pl', 'aaaaaa', NULL),
(37, 3, 'Fabian', 'Adamiak', '1111111111', 'fad@wp.pl', 'aaaaaa', NULL),
(38, 2, 'Adela', 'Juskowiak', '', 'jua@sp.pl', 'aaaaaa', 4),
(39, 3, 'Andrzej', 'Juskowiak', '38273813', 'jua@wp.pl', 'aaaaaa', NULL),
(40, 3, 'Natalia', 'Rutkowska', '1111111', 'rnat@wp.pl', 'aaaaaa', NULL),
(41, 2, 'Hanna', 'Majak', '9890', 'ham@sp.pl', 'aaaaaa', 1),
(42, 1, 'Agnieszka', 'Markuszewska', '5001990', 'mag@sp.pl', 'aaaaaa', NULL),
(45, 1, 'Jan', 'LubaĹski', '121', 'jlu@sp.pl', 'aaaaaa', NULL),
(49, 3, 'Jerzy', 'Nowacki', '', 'noj@wp.pl', 'aaaaaa', NULL),
(55, 3, 'Maria', 'Czernicka', '', 'czmar@wp.pl', 'aaaaaa', NULL),
(56, 3, 'Jan', 'Czernicki', '', 'czerj@wp.pl', 'aaaaaaa', NULL),
(60, 2, 'Jan', 'Krzykola', '', 'jk@pl.pl', 'aaaaaaa', 24),
(61, 3, 'Gggg', 'Ttt', '', 'tg@pl.pl', 'aaaaaaa', NULL),
(64, 2, 'Gggghh', 'Ggg', '', 'fg@pk.pl', 'gggghhhh', 21);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id_wiadomosci` int(11) NOT NULL,
  `id_nadawcy` int(11) NOT NULL,
  `id_odbiorcy` int(11) NOT NULL,
  `tresc` text COLLATE utf8_polish_ci NOT NULL,
  `przeczytana` tinyint(1) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `wiadomosci`
--

INSERT INTO `wiadomosci` (`id_wiadomosci`, `id_nadawcy`, `id_odbiorcy`, `tresc`, `przeczytana`, `data`) VALUES
(3, 5, 2, 'test2', 1, '2018-06-13'),
(5, 2, 22, 'hgjljjklljj', 0, '2018-06-22'),
(6, 2, 34, 'dsfdfsdfsdfsdf', 0, '2018-06-22'),
(9, 5, 2, '"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"', 1, '2018-06-22'),
(11, 5, 3, 'do adamiakÃ³w', 0, '2018-06-22'),
(13, 5, 3, 'a2', 0, '2018-06-22'),
(14, 5, 2, 'xxxxxxxxxx', 0, '2018-06-22'),
(15, 5, 37, 'xxxxxxxxxx', 0, '2018-06-22'),
(16, 5, 2, 'do caÅ‚ek klasy 1A', 0, '2018-06-22'),
(17, 5, 37, 'do caÅ‚ek klasy 1A', 1, '2018-06-22'),
(18, 5, 26, 'do caÅ‚ek klasy 1A', 0, '2018-06-22'),
(19, 5, 20, 'do caÅ‚ek klasy 1A', 1, '2018-06-22'),
(20, 5, 26, 'do caÅ‚ek klasy 1A', 0, '2018-06-22'),
(21, 5, 29, 'do caÅ‚ek klasy 1A', 0, '2018-06-22'),
(22, 5, 2, 'do klasy 4A', 0, '2018-06-22'),
(23, 5, 37, 'do klasy 4A', 1, '2018-06-22'),
(25, 5, 26, 'fhhgfh', 0, '2018-06-22'),
(26, 5, 2, 'hghf', 0, '2018-06-22'),
(27, 5, 37, 'hghf', 0, '2018-06-22'),
(28, 5, 2, 'test wiad do wszystkich rodz 1A', 1, '2018-06-24'),
(29, 5, 37, 'test wiad do wszystkich rodz 1A', 0, '2018-06-24'),
(30, 5, 26, 'test wiad do wszystkich rodz 1A', 0, '2018-06-24'),
(31, 5, 20, 'test wiad do wszystkich rodz 1A', 0, '2018-06-24'),
(32, 5, 26, 'test wiad do wszystkich rodz 1A', 0, '2018-06-24'),
(33, 5, 29, 'test wiad do wszystkich rodz 1A', 0, '2018-06-24'),
(34, 2, 35, 'Test wiadomoĹci\r\n', 0, '2018-06-25'),
(35, 2, 5, 'Test wiadomoĹci do Anna Polonistki', 0, '2018-06-25'),
(36, 5, 2, 'Co z tym usprawiedliwieniem??', 0, '2018-06-25'),
(37, 5, 37, 'Co z tym usprawiedliwieniem??', 0, '2018-06-25'),
(38, 2, 5, 'Halo jest tu ktođ', 0, '2018-06-29'),
(40, 5, 37, 'Tyhgfd', 0, '2018-06-29'),
(41, 5, 39, 'Tyhgfd', 0, '2018-06-29'),
(42, 5, 29, 'Test', 0, '2018-06-29'),
(43, 5, 40, 'Test', 0, '2018-06-29'),
(45, 2, 5, 'test wiadomoĹÄ', 1, '2018-07-14'),
(47, 5, 37, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(48, 5, 26, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(49, 5, 20, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(50, 5, 26, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(51, 5, 29, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(52, 5, 40, 'Test do wszystkich w 1A', 0, '2018-07-14'),
(54, 5, 37, 'Test do rodzicĂłw Adamiak MichaĹ', 0, '2018-07-14'),
(56, 5, 37, 'Do rodzicĂłw uczniĂłw klasy 4A', 0, '2018-07-14'),
(57, 5, 39, 'Do rodzicĂłw uczniĂłw klasy 4A', 0, '2018-07-14'),
(58, 2, 5, 'Test wiadomoĹci wysĹanej z menu Oceny', 0, '2018-07-14'),
(59, 2, 5, 'test wiadomoĹci wysĹanej z poziomu "Uwagi"', 0, '2018-07-14'),
(60, 5, 2, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 1, '2018-07-14'),
(61, 5, 37, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(62, 5, 26, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(63, 5, 55, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(64, 5, 56, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(65, 5, 20, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(66, 5, 26, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(67, 5, 29, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(68, 5, 40, 'WiadomoĹÄ testowa 1 do wszystkich rodzicĂłw w klasie', 0, '2018-07-14'),
(69, 5, 2, 'WiadomoĹÄ testowa 2 do rodzicĂłw ucznia 1', 1, '2018-07-14'),
(70, 5, 37, 'WiadomoĹÄ testowa 2 do rodzicĂłw ucznia 1', 0, '2018-07-14'),
(71, 5, 2, 'Hello ', 0, '2018-07-15'),
(72, 5, 37, 'Hello ', 0, '2018-07-15'),
(73, 5, 39, 'Test do juskowiaka', 0, '2018-07-15');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `dni`
--
ALTER TABLE `dni`
  ADD PRIMARY KEY (`id_dnia`),
  ADD UNIQUE KEY `nazwa` (`dzien`);

--
-- Indexes for table `godziny`
--
ALTER TABLE `godziny`
  ADD PRIMARY KEY (`id_godziny`);

--
-- Indexes for table `kategorie_ocen`
--
ALTER TABLE `kategorie_ocen`
  ADD PRIMARY KEY (`id_kategorii`);

--
-- Indexes for table `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id_klasy`),
  ADD UNIQUE KEY `nazwa` (`klasa`);

--
-- Indexes for table `kontakty`
--
ALTER TABLE `kontakty`
  ADD KEY `id_kontaktu` (`id_kontaktu`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indexes for table `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  ADD PRIMARY KEY (`id_nieobecnosci`),
  ADD KEY `id_rodzica` (`id_rodzica`),
  ADD KEY `id_ucznia` (`id_ucznia`);

--
-- Indexes for table `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`symbol`);

--
-- Indexes for table `oceny_ucznia`
--
ALTER TABLE `oceny_ucznia`
  ADD PRIMARY KEY (`id_oceny`),
  ADD KEY `id_kategorii` (`id_kategorii`),
  ADD KEY `id_przedmiotuklasy` (`id_przedmiotuklasy`),
  ADD KEY `id_ucznia` (`id_ucznia`),
  ADD KEY `symbol` (`symbol`);

--
-- Indexes for table `plany_lekcji`
--
ALTER TABLE `plany_lekcji`
  ADD KEY `id_dnia` (`id_dnia`),
  ADD KEY `id_godziny` (`id_godziny`),
  ADD KEY `id_przedmiotuklasy` (`id_przedmiotuklasy`);

--
-- Indexes for table `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id_przedmiotu`);

--
-- Indexes for table `przedmioty_klasy`
--
ALTER TABLE `przedmioty_klasy`
  ADD PRIMARY KEY (`id_przedmiotuklasy`),
  ADD KEY `id_nauczyciela` (`id_nauczyciela`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `przedmioty_klasy_ibfk_2` (`id_klasy`);

--
-- Indexes for table `rodzice_uczniowie`
--
ALTER TABLE `rodzice_uczniowie`
  ADD KEY `id_rodzica` (`id_rodzica`),
  ADD KEY `id_ucznia` (`id_ucznia`);

--
-- Indexes for table `sprawdziany`
--
ALTER TABLE `sprawdziany`
  ADD PRIMARY KEY (`id_sprawdzianu`),
  ADD KEY `id_przedmiotuklasy` (`id_przedmiotuklasy`);

--
-- Indexes for table `uwagi`
--
ALTER TABLE `uwagi`
  ADD PRIMARY KEY (`id_uwagi`),
  ADD KEY `id_nauczyciela` (`id_nauczyciela`),
  ADD KEY `id_ucznia` (`id_ucznia`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id_uzytkownika`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `uzytkownicy_ibfk_1` (`id_klasy`);

--
-- Indexes for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD PRIMARY KEY (`id_wiadomosci`),
  ADD KEY `id_nadawcy` (`id_nadawcy`),
  ADD KEY `id_odbiorcy` (`id_odbiorcy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kategorie_ocen`
--
ALTER TABLE `kategorie_ocen`
  MODIFY `id_kategorii` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `klasy`
--
ALTER TABLE `klasy`
  MODIFY `id_klasy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT dla tabeli `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  MODIFY `id_nieobecnosci` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT dla tabeli `oceny_ucznia`
--
ALTER TABLE `oceny_ucznia`
  MODIFY `id_oceny` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT dla tabeli `przedmioty_klasy`
--
ALTER TABLE `przedmioty_klasy`
  MODIFY `id_przedmiotuklasy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT dla tabeli `sprawdziany`
--
ALTER TABLE `sprawdziany`
  MODIFY `id_sprawdzianu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  MODIFY `id_uwagi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id_uzytkownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  MODIFY `id_wiadomosci` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `kontakty`
--
ALTER TABLE `kontakty`
  ADD CONSTRAINT `kontakty_ibfk_1` FOREIGN KEY (`id_kontaktu`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `kontakty_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `nieobecnosci`
--
ALTER TABLE `nieobecnosci`
  ADD CONSTRAINT `nieobecnosci_ibfk_1` FOREIGN KEY (`id_rodzica`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `nieobecnosci_ibfk_2` FOREIGN KEY (`id_ucznia`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `oceny_ucznia`
--
ALTER TABLE `oceny_ucznia`
  ADD CONSTRAINT `oceny_ucznia_ibfk_1` FOREIGN KEY (`id_kategorii`) REFERENCES `kategorie_ocen` (`id_kategorii`),
  ADD CONSTRAINT `oceny_ucznia_ibfk_2` FOREIGN KEY (`id_przedmiotuklasy`) REFERENCES `przedmioty_klasy` (`id_przedmiotuklasy`),
  ADD CONSTRAINT `oceny_ucznia_ibfk_3` FOREIGN KEY (`id_ucznia`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `oceny_ucznia_ibfk_4` FOREIGN KEY (`symbol`) REFERENCES `oceny` (`symbol`);

--
-- Ograniczenia dla tabeli `plany_lekcji`
--
ALTER TABLE `plany_lekcji`
  ADD CONSTRAINT `plany_lekcji_ibfk_1` FOREIGN KEY (`id_dnia`) REFERENCES `dni` (`id_dnia`),
  ADD CONSTRAINT `plany_lekcji_ibfk_2` FOREIGN KEY (`id_godziny`) REFERENCES `godziny` (`id_godziny`),
  ADD CONSTRAINT `plany_lekcji_ibfk_3` FOREIGN KEY (`id_przedmiotuklasy`) REFERENCES `przedmioty_klasy` (`id_przedmiotuklasy`);

--
-- Ograniczenia dla tabeli `przedmioty_klasy`
--
ALTER TABLE `przedmioty_klasy`
  ADD CONSTRAINT `przedmioty_klasy_ibfk_1` FOREIGN KEY (`id_nauczyciela`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `przedmioty_klasy_ibfk_2` FOREIGN KEY (`id_klasy`) REFERENCES `klasy` (`id_klasy`) ON DELETE CASCADE,
  ADD CONSTRAINT `przedmioty_klasy_ibfk_3` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id_przedmiotu`);

--
-- Ograniczenia dla tabeli `rodzice_uczniowie`
--
ALTER TABLE `rodzice_uczniowie`
  ADD CONSTRAINT `rodzice_uczniowie_ibfk_1` FOREIGN KEY (`id_rodzica`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `rodzice_uczniowie_ibfk_2` FOREIGN KEY (`id_ucznia`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `sprawdziany`
--
ALTER TABLE `sprawdziany`
  ADD CONSTRAINT `sprawdziany_ibfk_1` FOREIGN KEY (`id_przedmiotuklasy`) REFERENCES `przedmioty_klasy` (`id_przedmiotuklasy`);

--
-- Ograniczenia dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  ADD CONSTRAINT `uwagi_ibfk_1` FOREIGN KEY (`id_nauczyciela`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `uwagi_ibfk_2` FOREIGN KEY (`id_ucznia`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

--
-- Ograniczenia dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD CONSTRAINT `uzytkownicy_ibfk_1` FOREIGN KEY (`id_klasy`) REFERENCES `klasy` (`id_klasy`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD CONSTRAINT `wiadomosci_ibfk_1` FOREIGN KEY (`id_nadawcy`) REFERENCES `uzytkownicy` (`id_uzytkownika`),
  ADD CONSTRAINT `wiadomosci_ibfk_2` FOREIGN KEY (`id_odbiorcy`) REFERENCES `uzytkownicy` (`id_uzytkownika`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
