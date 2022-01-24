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
                                 account_id character varying(36) NOT NULL,
                                 account_username character varying(255) NOT NULL,
                                 account_email character varying(255) NOT NULL,
                                 account_password character varying(255) NOT NULL,
                                 account_is_active boolean NOT NULL
);


ALTER TABLE public.accounts OWNER TO rpgidlegame;


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
-- Data for Name: accounts; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.accounts (account_id, account_username, account_email, account_password, account_is_active) FROM stdin;
\.


--
-- Data for Name: characters; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.characters (character_id, character_owner, character_name, character_skill_points, character_health, character_attack, character_defense, character_magik, character_rank, character_fights_count) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: rpgidlegame
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
Kishlin\\Migrations\\Version20220121150906	2022-01-21 15:14:29	9
\.


--
-- Name: accounts accounts_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.accounts
    ADD CONSTRAINT accounts_pkey PRIMARY KEY (account_id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: rpgidlegame
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- PostgreSQL database dump complete
--

