--
-- PostgreSQL database dump
--

-- Dumped from database version 13.3 (Debian 13.3-1.pgdg100+1)
-- Dumped by pg_dump version 13.3 (Debian 13.3-1.pgdg100+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: accounts; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.accounts (
    id character varying(36) NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    salt character varying(255) NOT NULL,
    is_active boolean NOT NULL
);


ALTER TABLE public.accounts OWNER TO rpgidlegame;

--
-- Name: character_counts; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.character_counts (
    owner_id character varying(36) NOT NULL,
    character_count integer NOT NULL,
    reached_limit boolean NOT NULL
);


ALTER TABLE public.character_counts OWNER TO rpgidlegame;

--
-- Name: characters; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.characters (
    id character varying(36) NOT NULL,
    owner character varying(36) NOT NULL,
    name character varying(255) NOT NULL,
    skill_points integer NOT NULL,
    health integer NOT NULL,
    attack integer NOT NULL,
    defense integer NOT NULL,
    magik integer NOT NULL,
    rank integer NOT NULL,
    fights_count integer DEFAULT 0 NOT NULL,
    wins_count integer DEFAULT 0 NOT NULL,
    draws_count integer DEFAULT 0 NOT NULL,
    losses_count integer DEFAULT 0 NOT NULL,
    is_active boolean NOT NULL
);


ALTER TABLE public.characters OWNER TO rpgidlegame;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO rpgidlegame;

--
-- Name: fight_initiators; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.fight_initiators (
    id character varying(36) NOT NULL,
    character_id character varying(36) NOT NULL,
    health integer NOT NULL,
    attack integer NOT NULL,
    defense integer NOT NULL,
    magik integer NOT NULL,
    rank integer NOT NULL
);


ALTER TABLE public.fight_initiators OWNER TO rpgidlegame;

--
-- Name: fight_opponents; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.fight_opponents (
    id character varying(36) NOT NULL,
    character_id character varying(36) NOT NULL,
    health integer NOT NULL,
    attack integer NOT NULL,
    defense integer NOT NULL,
    magik integer NOT NULL,
    rank integer NOT NULL
);


ALTER TABLE public.fight_opponents OWNER TO rpgidlegame;

--
-- Name: fight_turns; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.fight_turns (
    id character varying(36) NOT NULL,
    fight_id character varying(255) NOT NULL,
    attacker_id character varying(36) NOT NULL,
    index integer NOT NULL,
    attacker_attack integer NOT NULL,
    attacker_magik integer NOT NULL,
    attacker_dice_roll integer NOT NULL,
    defender_defense integer NOT NULL,
    damage_dealt integer NOT NULL,
    defender_health integer NOT NULL
);


ALTER TABLE public.fight_turns OWNER TO rpgidlegame;

--
-- Name: fights; Type: TABLE; Schema: public; Owner: rpgidlegame
--

CREATE TABLE public.fights (
    id character varying(36) NOT NULL,
    initiator character varying(255) NOT NULL,
    opponent character varying(255) NOT NULL,
    winner_id character varying(36) DEFAULT NULL::character varying
);


ALTER TABLE public.fights OWNER TO rpgidlegame;

--
-- Data for Name: accounts; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.accounts (id, username, email, password, salt, is_active) FROM stdin;
\.


--
-- Data for Name: character_counts; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.character_counts (owner_id, character_count, reached_limit) FROM stdin;
\.


--
-- Data for Name: characters; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.characters (id, owner, name, skill_points, health, attack, defense, magik, rank, fights_count, wins_count, draws_count, losses_count, is_active) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Version20220121150906	2022-01-21 15:14:29	9
Kishlin\\Migrations\\Version20220124185735	2022-01-24 18:58:14	11
Kishlin\\Migrations\\Version20220125013925	2022-01-25 01:40:16	12
Kishlin\\Migrations\\Version20220201163853	2022-02-02 05:48:48	19
Kishlin\\Migrations\\Version20220207181914	2022-02-07 18:25:57	6
Kishlin\\Migrations\\Version20220228131426	2022-02-28 12:16:40	7
\.


--
-- Data for Name: fight_initiators; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.fight_initiators (id, character_id, health, attack, defense, magik, rank) FROM stdin;
\.


--
-- Data for Name: fight_opponents; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.fight_opponents (id, character_id, health, attack, defense, magik, rank) FROM stdin;
\.


--
-- Data for Name: fight_turns; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.fight_turns (id, fight_id, attacker_id, index, attacker_attack, attacker_magik, attacker_dice_roll, defender_defense, damage_dealt, defender_health) FROM stdin;
\.


--
-- Data for Name: fights; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.fights (id, initiator, opponent, winner_id) FROM stdin;
\.


--
-- Name: accounts accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.accounts
    ADD CONSTRAINT accounts_pkey PRIMARY KEY (id);


--
-- Name: character_counts character_counts_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.character_counts
    ADD CONSTRAINT character_counts_pkey PRIMARY KEY (owner_id);


--
-- Name: characters characters_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.characters
    ADD CONSTRAINT characters_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: fight_initiators fight_initiators_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_initiators
    ADD CONSTRAINT fight_initiators_pkey PRIMARY KEY (id);


--
-- Name: fight_opponents fight_opponents_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_opponents
    ADD CONSTRAINT fight_opponents_pkey PRIMARY KEY (id);


--
-- Name: fight_turns fight_turns_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_turns
    ADD CONSTRAINT fight_turns_pkey PRIMARY KEY (id);


--
-- Name: fights fights_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fights
    ADD CONSTRAINT fights_pkey PRIMARY KEY (id);


--
-- Name: idx_15531764ac6657e4; Type: INDEX; Schema: public; Owner: rpgidlegame
--

CREATE INDEX idx_15531764ac6657e4 ON public.fight_turns USING btree (fight_id);


--
-- Name: idx_9927918e451bf597; Type: INDEX; Schema: public; Owner: rpgidlegame
--

CREATE INDEX idx_9927918e451bf597 ON public.fights USING btree (initiator);


--
-- Name: idx_9927918ea9322aff; Type: INDEX; Schema: public; Owner: rpgidlegame
--

CREATE INDEX idx_9927918ea9322aff ON public.fights USING btree (opponent);


--
-- Name: fight_turns fk_15531764ac6657e4; Type: FK CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_turns
    ADD CONSTRAINT fk_15531764ac6657e4 FOREIGN KEY (fight_id) REFERENCES public.fights(id);


--
-- Name: fights fk_9927918e451bf597; Type: FK CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fights
    ADD CONSTRAINT fk_9927918e451bf597 FOREIGN KEY (initiator) REFERENCES public.fight_initiators(id);


--
-- Name: fights fk_9927918ea9322aff; Type: FK CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fights
    ADD CONSTRAINT fk_9927918ea9322aff FOREIGN KEY (opponent) REFERENCES public.fight_opponents(id);


--
-- Name: fight_initiators fk_initiator_character; Type: FK CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_initiators
    ADD CONSTRAINT fk_initiator_character FOREIGN KEY (character_id) REFERENCES public.characters(id);


--
-- Name: fight_opponents fk_opponent_character; Type: FK CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.fight_opponents
    ADD CONSTRAINT fk_opponent_character FOREIGN KEY (character_id) REFERENCES public.characters(id);


--
-- PostgreSQL database dump complete
--

